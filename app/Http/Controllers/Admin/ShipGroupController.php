<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shipgroup;
use Illuminate\Http\Request;

class ShipGroupController extends Controller
{
    public function index()
    {
        $shipGroups = Shipgroup::latest()->paginate(10);
        return view('admin.ship_groups.index', compact('shipGroups'));
    }

    public function create()
    {
        return view('admin.ship_groups.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'groupid' => 'nullable|string|max:255|unique:shipgroup,groupid',
        ]);

        Shipgroup::create($validated);

        return redirect()->route('admin.ship-groups.index')
            ->with('status', 'تم إضافة مجموعة السفن بنجاح');
    }

    public function edit(Shipgroup $shipGroup)
    {
        return view('admin.ship_groups.edit', compact('shipGroup'));
    }

    public function update(Request $request, Shipgroup $shipGroup)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'groupid' => 'nullable|string|max:255|unique:shipgroup,groupid,' . $shipGroup->id,
        ]);

        $shipGroup->update($validated);

        return redirect()->route('admin.ship-groups.index')
            ->with('status', 'تم تحديث مجموعة السفن بنجاح');
    }

    public function destroy(Shipgroup $shipGroup)
    {
        $shipGroup->delete();

        return redirect()->route('admin.ship-groups.index')
            ->with('status', 'تم حذف مجموعة السفن بنجاح');
    }
}
