<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomsPort;
use Illuminate\Http\Request;

class CustomsPortController extends Controller
{
    public function index()
    {
        $customsPorts = CustomsPort::latest()->paginate(10);
        return view('admin.customs_ports.index', compact('customsPorts'));
    }

    public function create()
    {
        return view('admin.customs_ports.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255|unique:customs_port,code',
            'type' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);

        CustomsPort::create($validated);

        return redirect()->route('admin.customs-ports.index')
            ->with('status', 'تم إضافة المنفذ الجمركي بنجاح');
    }

    public function edit(CustomsPort $customsPort)
    {
        return view('admin.customs_ports.edit', compact('customsPort'));
    }

    public function update(Request $request, CustomsPort $customsPort)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255|unique:customs_port,code,' . $customsPort->id,
            'type' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);

        $customsPort->update($validated);

        return redirect()->route('admin.customs-ports.index')
            ->with('status', 'تم تحديث المنفذ الجمركي بنجاح');
    }

    public function destroy(CustomsPort $customsPort)
    {
        $customsPort->delete();

        return redirect()->route('admin.customs-ports.index')
            ->with('status', 'تم حذف المنفذ الجمركي بنجاح');
    }
}
