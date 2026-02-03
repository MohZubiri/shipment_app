<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::latest()->paginate(10);
        return view('admin.documents.index', compact('documents'));
    }

    public function create()
    {
        return view('admin.documents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Document::create($validated);

        return redirect()->route('admin.documents.index')
            ->with('status', 'تم إضافة المستند بنجاح');
    }

    public function edit(Document $document)
    {
        return view('admin.documents.edit', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $document->update($validated);

        return redirect()->route('admin.documents.index')
            ->with('status', 'تم تحديث المستند بنجاح');
    }

    public function destroy(Document $document)
    {
        $document->delete();

        return redirect()->route('admin.documents.index')
            ->with('status', 'تم حذف المستند بنجاح');
    }
}
