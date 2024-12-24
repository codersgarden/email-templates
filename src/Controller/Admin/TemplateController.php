<?php

namespace Codersgarden\MultiLangMailer\Controller\Admin;

use Codersgarden\MultiLangMailer\Controller\Controller;
use Codersgarden\MultiLangMailer\Models\Placeholder;
use Codersgarden\MultiLangMailer\Models\DMailTemplate;
use Illuminate\Support\Facades\Request;

class TemplateController extends Controller
{
    /**
     * Display a listing of the templates.
     */
    public function index()
    {
        // Execute the query
        $templates = DMailTemplate::all();
        return view('email-templates::admin.templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new template.
     */
    public function create()
    {
        $locales = config('app.locales', ['en']);
        $availablePlaceholders = Placeholder::all();
        return view('email-templates::admin.templates.create', compact('locales', 'availablePlaceholders'));
    }

    /**
     * Store a newly created template in storage.
     */
    public function store(Request $request)
    {
        $locales = config('app.locales', ['en']);

        // Validate input
        $validated = $request->validate([
            'identifier' => 'required|unique:templates,identifier',
            'translations' => 'required|array',
            'translations.*.subject' => 'required|string',
            'translations.*.body' => 'required|string',
            'placeholders' => 'nullable|array',
            'placeholders.*' => 'exists:placeholders,id',
        ]);

        // Create template
        $template = DMailTemplate::create(['identifier' => $validated['identifier']]);

        // Attach placeholders
        if (isset($validated['placeholders'])) {
            $template->placeholders()->attach($validated['placeholders']);
        }

        // Create translations
        foreach ($validated['translations'] as $locale => $translation) {
            $template->translations()->create([
                'locale' => $locale,
                'subject' => $translation['subject'],
                'body' => $translation['body'],
            ]);
        }

        return redirect()->route('email-templates.admin.templates.index')->with('success', __('email-templates::messages.template_created'));
    }

    /**
     * Show the form for editing the specified template.
     */
    public function edit(DMailTemplate $template)
    {
        $locales = config('app.locales', ['en']);
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
