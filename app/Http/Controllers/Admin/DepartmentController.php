<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Company::latest()->paginate(10);
        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('admin.departments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:companies,name',
        ]);

        Company::create($validated);

        return redirect()->route('admin.departments.index')
            ->with('status', 'تم إضافة الشركة بنجاح');
    }

    public function edit(Company $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    public function update(Request $request, Company $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:companies,name,' . $department->id,
        ]);

        $department->update($validated);

        return redirect()->route('admin.departments.index')
            ->with('status', 'تم تحديث الشركة بنجاح');
    }

    public function destroy(Company $department)
    {
        $department->delete();

        return redirect()->route('admin.departments.index')
            ->with('status', 'تم حذف الشركة بنجاح');
    }
}
