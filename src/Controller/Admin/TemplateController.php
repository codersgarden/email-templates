<?php

namespace Codersgarden\MultiLangMailer\Controller\Admin;

use Codersgarden\MultiLangMailer\Controller\Controller;
use Codersgarden\MultiLangMailer\Models\Placeholder;
use Codersgarden\MultiLangMailer\Models\MailTemplate;
use Codersgarden\MultiLangMailer\Models\TemplatePlaceholder;
use Codersgarden\MultiLangMailer\Models\TemplateTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    /**
     * Display a listing of the templates.
     */

    public function index()
    {
        // Execute the query
        $templates = MailTemplate::orderBy('id', 'desc')->paginate(10);
        return view('email-templates::admin.templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new template.
     */
    public function create()
    {
        $locales = config('email-templates.supported_locales', ['en']);
        $availablePlaceholders = Placeholder::all();
        return view('email-templates::admin.templates.create', compact('locales', 'availablePlaceholders'));
    }

    /**
     * Store a newly created template in storage.
     */
    public function store(Request $request)
    {

       
        // Validate input
        $validated = $request->validate([
            'identifier' => 'required|unique:mail_templates,identifier',
            'translations' => 'array',
            'translations.*.subject' => 'string',
            'translations.*.body' => 'string',
            'placeholders' => 'nullable|array|min:1',
            'placeholders.*' => 'exists:placeholders,id',
           
        ]);


        try {

        $template=new MailTemplate();
        $template->identifier=$request->identifier;
        $template->has_attachment = $request->has('attachment') ? 1 : 0;
        $template->save();

           
            foreach ($validated['placeholders'] as $placeholder) {
                $TemplatesPlaceholders = new TemplatePlaceholder();
                $TemplatesPlaceholders->placeholder_id = $placeholder;
                $TemplatesPlaceholders->mail_template_id = $template->id;
                $TemplatesPlaceholders->save();
            }

            // Create translations
            foreach ($validated['translations'] as $locale => $translation) {
                $TemplateTranslation = new TemplateTranslation();
                $TemplateTranslation->mail_template_id = $template->id;
                $TemplateTranslation->locale = $locale;
                $TemplateTranslation->subject = $translation['subject'];
                $TemplateTranslation->body = $translation['body'];
                $TemplateTranslation->save();
            }

            return redirect()->route('admin.templates.index')->with('success', __('email-templates::messages.template_created'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('admin.templates.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified template.
     */
    public function edit($id)
    {
        // Fetch the mail template
        $template = MailTemplate::with(['placeholders', 'translations'])->findOrFail($id);

        // Supported locales from configuration
        $locales = config('email-templates.supported_locales', ['en']);

        // All available placeholders
        $availablePlaceholders = Placeholder::all();

        // Selected placeholder IDs for this template
        $selectedPlaceholders = $template->placeholders->pluck('id')->toArray();

        // Translations for all supported locales
        $translations = [];
        foreach ($locales as $locale) {
            $translations[$locale] = $template->translations->where('locale', $locale)->first() ?? ['subject' => '', 'body' => ''];
        }

        // Return to the edit view
        return view('email-templates::admin.templates.edit', compact(
            'template',
            'locales',
            'availablePlaceholders',
            'selectedPlaceholders',
            'translations'
        ));
    }

    /**
     * Update the specified template in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate input
        $validated = $request->validate([
            'identifier' => 'required|unique:mail_templates,identifier,' . $id,
            'translations' => 'array',
            'translations.*.subject' => 'string',
            'translations.*.body' => 'string',
            'placeholders' => 'nullable|array',
            'placeholders.*' => 'exists:placeholders,id',
          
        ]);

        try {
            $template = MailTemplate::findOrFail($id);

            $template->identifier=$request->identifier;
            $template->has_attachment = $request->has('attachment') ? 1 : 0;
            $template->save();

            // Handle placeholders
            $template->placeholders()->detach();
            if (!empty($validated['placeholders'])) {
                foreach ($validated['placeholders'] as $placeholderId) {
                    $templatePlaceholder = new TemplatePlaceholder();
                    $templatePlaceholder->mail_template_id = $template->id;
                    $templatePlaceholder->placeholder_id = $placeholderId;
                    $templatePlaceholder->save();
                }
            }

            // Delete existing translations and save new ones
            TemplateTranslation::where('mail_template_id', $template->id)->delete();
            foreach ($validated['translations'] as $locale => $translation) {
                $templateTranslation = new TemplateTranslation();
                $templateTranslation->mail_template_id = $template->id;
                $templateTranslation->locale = $locale;
                $templateTranslation->subject = $translation['subject'];
                $templateTranslation->body = $translation['body'];
                $templateTranslation->save();
            }

            // Return success response
            return redirect()->route('admin.templates.index')->with('success', __('email-templates::messages.template_updated'));
        } catch (\Exception $e) {
            // Log error and return error message
            Log::error($e->getMessage());
            return redirect()->route('admin.templates.index')->with('error', $e->getMessage());
        }
    }



    /**
     * Remove the specified template from storage.
     */
    public function destroy($id)
    {
        try {
            // Find the template to delete
            $template = MailTemplate::findOrFail($id);

            // Delete the associated placeholders and translations
            $template->placeholders()->detach();
            TemplateTranslation::where('mail_template_id', $template->id)->delete();

            // Delete the template itself
            $template->delete();

            return redirect()->route('admin.templates.index')->with('success', __('email-templates::messages.template_deleted'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('admin.templates.index')->with('error', $e->getMessage());
        }
    }


    public function deleteFile(Request $request)
    {
        $request->validate([
            'file_name' => 'required|string',
            'template_id' => 'required|integer|exists:mail_templates,id',
        ]);

        try {
            // Fetch the template
            $template = MailTemplate::findOrFail($request->template_id);

            // Decode the stored files
            $files = $template->file ? explode(',', $template->file) : [];

            // Find and remove the file
            if (in_array($request->file_name, $files)) {
                $filePath = public_path('storage/images/' . $request->file_name);

                // Delete the file from the filesystem
                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                // Remove the file from the array and update the database
                $files = array_filter($files, fn($file) => $file !== $request->file_name);
                $template->file = implode(',', $files);
                $template->save();
            }

            return response()->json(['success' => true, 'message' => 'File deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
