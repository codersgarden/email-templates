<?php

namespace Codersgarden\MultiLangMailer\Controller\Admin;

use Codersgarden\MultiLangMailer\Controller\Controller;
use Codersgarden\MultiLangMailer\Models\Placeholder;
use Illuminate\Http\Request;

class PlaceholderController extends Controller
{
    public function index(Request $request)
    {
        $placeholders = Placeholder::all();
        return view('email-templates::admin.placeholders.index', compact('placeholders'));
    }

    public function create(Request $request)
    {
        return view('email-templates::admin.placeholders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'data_type' => 'required',
            'description' => 'required',
        ]);

        $placeholder = new Placeholder();
        $placeholder->name = $request->name;
        $placeholder->description = $request->description;
        $placeholder->data_type = $request->data_type;
        $placeholder->save();

        return redirect()->route('admin.placeholders.index')->with('success', __('email-templates::messages.placeholder_created'));
    }

    public function edit($id)
    {
        $placeholder = Placeholder::findOrFail($id);
        return view('email-templates::admin.placeholders.edit', compact('placeholder'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'data_type' => 'required',
            'description' => 'required',
        ]);

        $placeholder = Placeholder::findOrFail($id);
        $placeholder->name = $request->name;
        $placeholder->description = $request->description;
        $placeholder->data_type = $request->data_type;
        $placeholder->save();

        return redirect()->route('admin.placeholders.index')->with('success', __('email-templates::messages.placeholder_updated'));
    }

    // public function destroy($id)
    // {
    //     $placeholder = Placeholder::findOrFail($id);
    //     $placeholder->delete();
    //     return redirect()->route('admin.placeholders.index')->with('success', __('email-templates::messages.placeholder_deleted'));
    // }
}
