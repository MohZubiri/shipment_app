<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShipmentStage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ShipmentStageController extends Controller
{
    public function index()
    {
        $stages = ShipmentStage::orderBy('order')->get();

        return view('admin.shipment_stages.index', compact('stages'));
    }

    public function create()
    {
        return view('admin.shipment_stages.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['needs_containers'] = $request->boolean('needs_containers', false);
        ShipmentStage::create($data);

        return redirect()->route('admin.shipment-stages.index')
            ->with('status', 'تم إضافة المرحلة بنجاح');
    }

    public function edit(ShipmentStage $shipment_stage)
    {
        return view('admin.shipment_stages.edit', [
            'stage' => $shipment_stage,
        ]);
    }

    public function update(Request $request, ShipmentStage $shipment_stage)
    {
        $data = $this->validateData($request, $shipment_stage->id);
        $data['is_active'] = $request->boolean('is_active', false);
        $data['needs_containers'] = $request->boolean('needs_containers', false);
        $shipment_stage->update($data);

        return redirect()->route('admin.shipment-stages.index')
            ->with('status', 'تم تحديث المرحلة بنجاح');
    }

    public function destroy(ShipmentStage $shipment_stage)
    {
        $shipment_stage->delete();

        return redirect()->route('admin.shipment-stages.index')
            ->with('status', 'تم حذف المرحلة بنجاح');
    }

    private function validateData(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('shipment_stages', 'code')->ignore($id),
            ],
            'order' => ['required', 'integer', 'min:0'],
            'icon' => ['nullable', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:30'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
            'needs_containers' => ['sometimes', 'boolean'],
        ]);
    }
}
