<?php

namespace Codersgarden\MultiLangMailer\Controller\Admin;

use Codersgarden\MultiLangMailer\Controller\Controller;
use Codersgarden\MultiLangMailer\Models\Placeholder;
use Codersgarden\MultiLangMailer\Models\MailTemplate;
use Codersgarden\MultiLangMailer\Models\TemplatePlaceholder;
use Codersgarden\MultiLangMailer\Models\TemplateTranslation;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    /**
     * Display a listing of the templates.
     */
    public function index()
    {
        // Execute the query
        $templates = MailTemplate::all();
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
            'translations' => 'required|array',
            'translations.*.subject' => 'required|string',
            'translations.*.body' => 'required|string',
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
    public function edit(DMailTemplate $template)
    {
        $locales = config('email-templates.supported_locales', ['en']);
        $availablePlaceholders = Placeholder::all();
        $selectedPlaceholders = $template->placeholders->pluck('id')->toArray();
        return view('email-templates::admin.templates.edit', compact('template', 'locales', 'availablePlaceholders', 'selectedPlaceholders'));
    }

    /**
     * Update the specified template in storage.
     */
    public function update(Request $request, DMailTemplate $template)
    {
        $locales = config('app.locales', ['en']);

        // Validate input
        $validated = $request->validate([
            'translations' => 'required|array',
            'translations.*.subject' => 'required|string',
            'translations.*.body' => 'required|string',
            'placeholders' => 'nullable|array',
            'placeholders.*' => 'exists:placeholders,id',
        ]);

        // Update placeholders
        if (isset($validated['placeholders'])) {
            $template->placeholders()->sync($validated['placeholders']);
        } else {
            $template->placeholders()->detach();
        }

        // Update translations
        foreach ($validated['translations'] as $locale => $translation) {
            $template->translations()->updateOrCreate(
                ['locale' => $locale],
                ['subject' => $translation['subject'], 'body' => $translation['body']]
            );
        }

        return redirect()->route('email-templates.admin.templates.index')->with('success', __('email-templates::messages.template_updated'));
    }

    /**
     * Remove the specified template from storage.
     */
    public function destroy(DMailTemplate $template)
    {
        $template->delete();
        return redirect()->route('email-templates.admin.templates.index')->with('success', __('email-templates::messages.template_deleted'));
    }
}
