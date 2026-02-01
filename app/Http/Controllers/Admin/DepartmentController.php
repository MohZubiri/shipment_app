<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Departement;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Departement::latest()->paginate(10);
        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('admin.departments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departement,name',
        ]);

        Departement::create($validated);

        return redirect()->route('admin.departments.index')
            ->with('status', 'تم إضافة القسم بنجاح');
    }

    public function edit(Departement $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    public function update(Request $request, Departement $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departement,name,' . $department->id,
        ]);

        $department->update($validated);

        return redirect()->route('admin.departments.index')
            ->with('status', 'تم تحديث القسم بنجاح');
    }

    public function destroy(Departement $department)
    {
        $department->delete();

        return redirect()->route('admin.departments.index')
            ->with('status', 'تم حذف القسم بنجاح');
    }
}
