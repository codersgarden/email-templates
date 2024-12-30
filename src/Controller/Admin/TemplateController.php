<?php

namespace Codersgarden\MultiLangMailer\Controller\Admin;

use Codersgarden\MultiLangMailer\Controller\Controller;
use Codersgarden\MultiLangMailer\Models\Placeholder;
use Codersgarden\MultiLangMailer\Models\MailTemplate;
use Codersgarden\MultiLangMailer\Models\TemplatePlaceholder;
use Codersgarden\MultiLangMailer\Models\TemplateTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TemplateController extends Controller
{
    /**
     * Display a listing of the templates.
     */
    public function index()
    {
        // Execute the query
        $templates = MailTemplate::orderBy('id', 'desc')->paginate(2);
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
            'placeholders' => 'nullable|array',
            'placeholders.*' => 'exists:placeholders,id',
        ]);
        try {


            // Create template
            $template = MailTemplate::create(['identifier' => $validated['identifier']]);

            // Attach placeholders

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
            // Fetch the template to update
            $template = MailTemplate::findOrFail($id);

            // Update the identifier
            $template->identifier = $validated['identifier'];
            $template->save();

            // Remove existing placeholders and attach new ones
            $template->placeholders()->detach();
            if (!empty($validated['placeholders'])) {
                foreach ($validated['placeholders'] as $placeholderId) {
                    $templatePlaceholder = new TemplatePlaceholder();
                    $templatePlaceholder->mail_template_id = $template->id;
                    $templatePlaceholder->placeholder_id = $placeholderId;
                    $templatePlaceholder->save();
                }
            }

            // Delete existing translations for this template
            TemplateTranslation::where('mail_template_id', $template->id)->delete();

            // Create new translations
            foreach ($validated['translations'] as $locale => $translation) {
                $templateTranslation = new TemplateTranslation();
                $templateTranslation->mail_template_id = $template->id;
                $templateTranslation->locale = $locale;
                $templateTranslation->subject = $translation['subject'];
                $templateTranslation->body = $translation['body'];
                $templateTranslation->save();
            }

            return redirect()->route('admin.templates.index')->with('success', __('email-templates::messages.template_updated'));
        } catch (\Exception $e) {
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
}
