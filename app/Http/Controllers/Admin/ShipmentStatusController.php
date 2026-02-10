<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShipmentStatus;
use Illuminate\Http\Request;

class ShipmentStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view shipment statuses')->only(['index', 'show']);
        $this->middleware('permission:create shipment statuses')->only(['create', 'store']);
        $this->middleware('permission:edit shipment statuses')->only(['edit', 'update']);
        $this->middleware('permission:delete shipment statuses')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $shipmentStatuses = ShipmentStatus::latest()->paginate(10);
        return view('admin.shipment_statuses.index', compact('shipmentStatuses'));
    }

    public function create()
    {
        return view('admin.shipment_statuses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:shipment_statuses,name',
        ]);

        ShipmentStatus::create($validated);

        return redirect()->route('admin.shipment-statuses.index')
            ->with('status', 'تم إضافة حالة الشحنة بنجاح');
    }

    public function edit(ShipmentStatus $shipmentStatus)
    {
        return view('admin.shipment_statuses.edit', compact('shipmentStatus'));
    }

    public function update(Request $request, ShipmentStatus $shipmentStatus)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:shipment_statuses,name,' . $shipmentStatus->id,
        ]);

        $shipmentStatus->update($validated);

        return redirect()->route('admin.shipment-statuses.index')
            ->with('status', 'تم تحديث حالة الشحنة بنجاح');
    }

    public function destroy(ShipmentStatus $shipmentStatus)
    {
        $shipmentStatus->delete();

        return redirect()->route('admin.shipment-statuses.index')
            ->with('status', 'تم حذف حالة الشحنة بنجاح');
    }
}
