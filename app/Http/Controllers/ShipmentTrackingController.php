<?php

namespace App\Http\Controllers;

use App\Models\ShipmentStage;
use App\Models\ShipmentTransaction;
use App\Models\ShipmentTracking;
use Illuminate\Http\Request;

class ShipmentTrackingController extends Controller
{
    public function index(ShipmentTransaction $shipment)
    {
        $shipment->load(['trackingRecords.stage', 'trackingRecords.createdBy', 'currentStage']);

        $stages = ShipmentStage::active()->ordered()->forTransport('sea')->get();

        return view('shipments.tracking.index', [
            'shipment' => $shipment,
            'stages' => $stages,
        ]);
    }

    public function create(ShipmentTransaction $shipment)
    {
        $stages = ShipmentStage::active()->ordered()->forTransport('sea')->get();

        return view('shipments.tracking.create', [
            'shipment' => $shipment,
            'stages' => $stages,
        ]);
    }

    public function store(Request $request, ShipmentTransaction $shipment)
    {
        $stage = ShipmentStage::findOrFail($request->input('stage_id'));
        if (!in_array($stage->applies_to, ['sea', 'both'], true)) {
            return back()
                ->withInput()
                ->withErrors(['stage_id' => 'هذه المرحلة غير متاحة للشحن البحري.']);
        }

        // التحقق من عدم تكرار المرحلة في نفس اليوم
        $existingRecord = $shipment->trackingRecords()
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

        // التحقق من عدد الحاويات
        if ($stage->needs_containers && isset($data['container_count'])) {
            $remainingContainers = $shipment->getRemainingContainers($request->input('stage_id'));

            if ($data['container_count'] > $remainingContainers) {
                return back()
                    ->withInput()
                    ->withErrors(['container_count' => "عدد الحاويات المدخل يتجاوز العدد المتبقي. المتبقي: {$remainingContainers} حاوية"]);
            }

            // تحويل مصفوفة أرقام الحاويات إلى نص
            if (isset($data['container_numbers']) && is_array($data['container_numbers'])) {
                $data['container_numbers'] = implode("\n", array_filter($data['container_numbers']));
            }
        }

        $data['created_by'] = auth()->id();
        $data['event_date'] = $data['event_date'] ?? now();

        $shipment->addTrackingRecord($data);

        return redirect()
            ->route('shipments.tracking.index', $shipment)
            ->with('status', 'تم إضافة سجل التتبع بنجاح.');
    }

    public function edit(ShipmentTransaction $shipment, ShipmentTracking $tracking)
    {
        $stages = ShipmentStage::active()->ordered()->forTransport('sea')->get();

        return view('shipments.tracking.edit', [
            'shipment' => $shipment,
            'tracking' => $tracking,
            'stages' => $stages,
        ]);
    }

    public function update(Request $request, ShipmentTransaction $shipment, ShipmentTracking $tracking)
    {
        $stage = ShipmentStage::findOrFail($request->input('stage_id'));
        if (!in_array($stage->applies_to, ['sea', 'both'], true)) {
            return back()
                ->withInput()
                ->withErrors(['stage_id' => 'هذه المرحلة غير متاحة للشحن البحري.']);
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

        // التحقق من عدد الحاويات (باستثناء الحاويات في هذا السجل الحالي)
        if ($stage->needs_containers && isset($data['container_count'])) {
            $currentCount = $tracking->container_count ?? 0;
            $usedContainers = $shipment->getUsedContainers($request->input('stage_id')) - $currentCount;
            $totalContainers = $shipment->getTotalContainers();
            $remainingContainers = $totalContainers - $usedContainers;

            if ($data['container_count'] > $remainingContainers) {
                return back()
                    ->withInput()
                    ->withErrors(['container_count' => "عدد الحاويات المدخل يتجاوز العدد المتبقي. المتبقي: {$remainingContainers} حاوية"]);
            }

            // تحويل مصفوفة أرقام الحاويات إلى نص
            if (isset($data['container_numbers']) && is_array($data['container_numbers'])) {
                $data['container_numbers'] = implode("\n", array_filter($data['container_numbers']));
            }
        }

        $tracking->update($data);

        return redirect()
            ->route('shipments.tracking.index', $shipment)
            ->with('status', 'تم تحديث سجل التتبع بنجاح.');
    }

    public function destroy(ShipmentTransaction $shipment, ShipmentTracking $tracking)
    {
        $tracking->delete();

        return redirect()
            ->route('shipments.tracking.index', $shipment)
            ->with('status', 'تم حذف سجل التتبع بنجاح.');
    }

    public function show(ShipmentTransaction $shipment, $trackingId)
    {
        $tracking = $shipment->trackingRecords()->findOrFail($trackingId);

        return view('shipments.tracking.show', [
            'shipment' => $shipment,
            'tracking' => $tracking,
        ]);
    }

    public function getContainerInfo(Request $request, ShipmentTransaction $shipment)
    {
        $stageId = $request->input('stage_id');
        $currentContainerCount = $request->input('current_container_count', 0);

        $usedContainers = $shipment->getUsedContainers($stageId);
        $totalContainers = $shipment->getTotalContainers();
        $remainingContainers = $totalContainers - $usedContainers;
dd($stageId,$usedContainers,$totalContainers,$remainingContainers);
        return response()->json([
            'used' => $usedContainers,
            'remaining' => $remainingContainers,
        ]);
    }
}
