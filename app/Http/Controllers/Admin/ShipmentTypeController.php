<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShipmentType;
use Illuminate\Http\Request;

class ShipmentTypeController extends Controller
{
    public function index()
    {
        $shipmentTypes = ShipmentType::latest()->paginate(10);
        return view('admin.shipment_types.index', compact('shipmentTypes'));
    }

    public function create()
    {
        return view('admin.shipment_types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:shipment_types,name',
        ]);

        ShipmentType::create($validated);

        return redirect()->route('admin.shipment-types.index')
            ->with('status', 'تم إضافة نوع الشحنة بنجاح');
    }

    public function edit(ShipmentType $shipmentType)
    {
        return view('admin.shipment_types.edit', compact('shipmentType'));
    }

    public function update(Request $request, ShipmentType $shipmentType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:shipment_types,name,' . $shipmentType->id,
        ]);

        $shipmentType->update($validated);

        return redirect()->route('admin.shipment-types.index')
            ->with('status', 'تم تحديث نوع الشحنة بنجاح');
    }

    public function destroy(ShipmentType $shipmentType)
    {
        $shipmentType->delete();

        return redirect()->route('admin.shipment-types.index')
            ->with('status', 'تم حذف نوع الشحنة بنجاح');
    }
}
