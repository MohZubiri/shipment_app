<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index()
    {
        $sections = Section::latest()->paginate(10);
        return view('admin.sections.index', compact('sections'));
    }

    public function create()
    {
        return view('admin.sections.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:section,name',
        ]);

        Section::create($validated);

        return redirect()->route('admin.sections.index')
            ->with('status', 'تم إضافة القسم بنجاح');
    }

    public function edit(Section $section)
    {
        return view('admin.sections.edit', compact('section'));
    }

    public function update(Request $request, Section $section)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:section,name,' . $section->id,
        ]);

        $section->update($validated);

        return redirect()->route('admin.sections.index')
            ->with('status', 'تم تحديث القسم بنجاح');
    }

    public function destroy(Section $section)
    {
        $section->delete();

        return redirect()->route('admin.sections.index')
            ->with('status', 'تم حذف القسم بنجاح');
    }
}
