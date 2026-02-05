<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShipmentStage;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::withCount('stages')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.warehouses.index', compact('warehouses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $stages = ShipmentStage::active()->ordered()->get();
        return view('admin.warehouses.create', compact('stages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:warehouses',
            'location' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'capacity' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
            'stages' => 'nullable|array',
            'stages.*' => 'exists:shipment_stages,id',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $warehouse = Warehouse::create($validated);

        if (!empty($validated['stages'])) {
            $warehouse->stages()->sync($validated['stages']);
        }

        return redirect()->route('admin.warehouses.index')
            ->with('success', 'تم إنشاء المخزن بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Warehouse $warehouse)
    {
        $warehouse->load(['stages' => function($q) { $q->ordered(); }]);
        return view('admin.warehouses.show', compact('warehouse'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warehouse $warehouse)
    {
        $stages = ShipmentStage::active()->ordered()->get();
        $selectedStages = $warehouse->stages()->pluck('shipment_stages.id')->toArray();
        return view('admin.warehouses.edit', compact('warehouse', 'stages', 'selectedStages'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:50', Rule::unique('warehouses')->ignore($warehouse)],
            'location' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'capacity' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
            'stages' => 'nullable|array',
            'stages.*' => 'exists:shipment_stages,id',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $warehouse->update($validated);
        $warehouse->stages()->sync($validated['stages'] ?? []);

        return redirect()->route('admin.warehouses.index')
            ->with('success', 'تم تحديث المخزن بنجاح');
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();

        return redirect()->route('admin.warehouses.index')
            ->with('success', 'تم حذف المخزن بنجاح');
    }

    public function getWarehousesByStage($stageId)
    {
        $warehouses = Warehouse::active()
            ->whereHas('stages', function($query) use ($stageId) {
                $query->where('shipment_stages.id', $stageId);
            })
            ->get(['id', 'name', 'code']);

        return response()->json($warehouses);
    }
}
