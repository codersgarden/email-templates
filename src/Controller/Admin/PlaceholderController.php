<?php

namespace Codersgarden\MultiLangMailer\Controller\Admin;

use Codersgarden\MultiLangMailer\Controller\Controller;
use Codersgarden\MultiLangMailer\Modal\Placeholder;
use Illuminate\Http\Request;

class PlaceholderController extends Controller
{
    /**
     * Display a listing of the placeholders.
     */
    public function index()
    {
        $placeholders = Placeholder::all();
        return view('email-templates::admin.placeholders.index', compact('placeholders'));
    }

    /**
     * Show the form for creating a new placeholder.
     */
    public function create()
    {
        return view('email-templates::admin.placeholders.create');
    }

    /**
     * Store a newly created placeholder in storage.
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|unique:placeholders,name',
            'description' => 'nullable|string',
            'data_type' => 'nullable|string|in:string,date,integer',
        ]);

        // Create placeholder
        Placeholder::create($validated);

        return redirect()->route('email-templates.admin.placeholders.index')->with('success', __('email-templates::messages.placeholder_created'));
    }

    /**
     * Show the form for editing the specified placeholder.
     */
    public function edit(Placeholder $placeholder)
    {
        return view('email-templates::admin.placeholders.edit', compact('placeholder'));
    }

    /**
     * Update the specified placeholder in storage.
     */
    public function update(Request $request, Placeholder $placeholder)
    {
        // Validate input
        $validated = $request->validate([
            'description' => 'nullable|string',
            'data_type' => 'nullable|string|in:string,date,integer',
        ]);

        // Update placeholder
        $placeholder->update($validated);

        return redirect()->route('email-templates.admin.placeholders.index')->with('success', __('email-templates::messages.placeholder_updated'));
    }

    /**
     * Remove the specified placeholder from storage.
     */
    public function destroy(Placeholder $placeholder)
    {
        // Detach from templates first to maintain referential integrity
        $placeholder->templates()->detach();
        $placeholder->delete();

        return redirect()->route('email-templates.admin.placeholders.index')->with('success', __('email-templates::messages.placeholder_deleted'));
    }
}
