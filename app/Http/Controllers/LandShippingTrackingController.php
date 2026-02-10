<?php

namespace App\Http\Controllers;

use App\Models\LandShipping;
use App\Models\LandShippingTracking;
use App\Models\ShipmentStage;
use Illuminate\Http\Request;

class LandShippingTrackingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view tracking')->only(['index', 'show']);
        $this->middleware('permission:create tracking')->only(['create', 'store']);
        $this->middleware('permission:edit tracking')->only(['edit', 'update']);
        $this->middleware('permission:delete tracking')->only(['destroy']);
    }

    public function index(LandShipping $landShipping)
    {
        $landShipping->load(['trackingRecords.stage', 'trackingRecords.createdBy', 'currentStage']);

        $stages = ShipmentStage::active()->ordered()->forTransport('land')->get();

        return view('land_shipments.tracking.index', [
            'landShipping' => $landShipping,
            'stages' => $stages,
        ]);
    }

    public function create(LandShipping $landShipping)
    {
        $stages = ShipmentStage::active()->ordered()->forTransport('land')->get();

        return view('land_shipments.tracking.create', [
            'landShipping' => $landShipping,
            'stages' => $stages,
        ]);
    }

    public function store(Request $request, LandShipping $landShipping)
    {
        $stage = ShipmentStage::findOrFail($request->input('stage_id'));
        if (!in_array($stage->applies_to, ['land', 'both'], true)) {
            return back()
                ->withInput()
                ->withErrors(['stage_id' => 'هذه المرحلة غير متاحة للشحن البري.']);
        }

        $existingRecord = $landShipping->trackingRecords()
            ->where('stage_id', $stage->id)
            ->whereDate('event_date', now()->toDateString())
            ->first();

        if ($existingRecord) {
            return back()
                ->withInput()
                ->withErrors(['stage_id' => 'تم إضافة هذه المرحلة بالفعل في هذا اليوم']);
        }

        $data = $request->validate([
            'stage_id' => ['required', 'exists:shipment_stages,id'],
            'warehouse_id' => [$stage->needs_warehouse ? 'required' : 'nullable', 'exists:warehouses,id'],
            'event_date' => ['nullable', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'container_count' => [$stage->needs_containers ? 'required' : 'nullable', 'integer', 'min:1'],
            'container_numbers' => [$stage->needs_containers ? 'required' : 'nullable', 'array'],
            'container_numbers.*' => ['required', 'string'],
        ]);

        if ($stage->needs_containers && isset($data['container_numbers']) && is_array($data['container_numbers'])) {
            $data['container_numbers'] = implode("\n", array_filter($data['container_numbers']));
        }

        $data['created_by'] = auth()->id();
        $data['event_date'] = $data['event_date'] ?? now();

        $landShipping->addTrackingRecord($data);

        return redirect()
            ->route('road-shipments.tracking.index', $landShipping)
            ->with('status', 'تم إضافة سجل التتبع بنجاح.');
    }

    public function edit(LandShipping $landShipping, LandShippingTracking $tracking)
    {
        $stages = ShipmentStage::active()->ordered()->forTransport('land')->get();

        return view('land_shipments.tracking.edit', [
            'landShipping' => $landShipping,
            'tracking' => $tracking,
            'stages' => $stages,
        ]);
    }

    public function update(Request $request, LandShipping $landShipping, LandShippingTracking $tracking)
    {
        $stage = ShipmentStage::findOrFail($request->input('stage_id'));
        if (!in_array($stage->applies_to, ['land', 'both'], true)) {
            return back()
                ->withInput()
                ->withErrors(['stage_id' => 'هذه المرحلة غير متاحة للشحن البري.']);
        }

        $data = $request->validate([
            'stage_id' => ['required', 'exists:shipment_stages,id'],
            'warehouse_id' => [$stage->needs_warehouse ? 'required' : 'nullable', 'exists:warehouses,id'],
            'event_date' => ['nullable', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'container_count' => [$stage->needs_containers ? 'required' : 'nullable', 'integer', 'min:1'],
            'container_numbers' => [$stage->needs_containers ? 'required' : 'nullable', 'array'],
            'container_numbers.*' => ['required', 'string'],
        ]);

        if ($stage->needs_containers && isset($data['container_numbers']) && is_array($data['container_numbers'])) {
            $data['container_numbers'] = implode("\n", array_filter($data['container_numbers']));
        }

        $tracking->update($data);
        if ($landShipping->current_stage_id !== $tracking->stage_id) {
            $landShipping->update(['current_stage_id' => $tracking->stage_id]);
        }

        return redirect()
            ->route('road-shipments.tracking.index', $landShipping)
            ->with('status', 'تم تحديث سجل التتبع بنجاح.');
    }

    public function destroy(LandShipping $landShipping, LandShippingTracking $tracking)
    {
        $tracking->delete();

        return redirect()
            ->route('road-shipments.tracking.index', $landShipping)
            ->with('status', 'تم حذف سجل التتبع بنجاح.');
    }
}
