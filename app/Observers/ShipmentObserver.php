<?php

namespace App\Observers;

use App\Models\Alarm;
use App\Models\AuditLog;
use App\Models\Shipment;
use Illuminate\Support\Facades\Auth;

class ShipmentObserver
{
    /**
     * Handle the Shipment "saving" event.
     */
    public function saving(Shipment $shipment): void
    {
        $shipment->recalculateAllowances();
    }

    /**
     * Handle the Shipment "created" event.
     */
    public function created(Shipment $shipment): void
    {
        $this->logChange($shipment, 'created', [], $shipment->getAttributes());
    }

    /**
     * Handle the Shipment "updated" event.
     */
    public function updated(Shipment $shipment): void
    {
        $changes = $shipment->getChanges();
        $before = array_intersect_key($shipment->getOriginal(), $changes);

        $this->logChange($shipment, 'updated', $before, $changes);
    }

    /**
     * Handle the Shipment "deleted" event.
     */
    public function deleted(Shipment $shipment): void
    {
        //
    }

    /**
     * Handle the Shipment "restored" event.
     */
    public function restored(Shipment $shipment): void
    {
        //
    }

    /**
     * Handle the Shipment "force deleted" event.
     */
    public function forceDeleted(Shipment $shipment): void
    {
        //
    }

    /**
     * Handle the Shipment "saved" event.
     */
    public function saved(Shipment $shipment): void
    {
        $this->syncAlarm($shipment);
    }

    private function logChange(Shipment $shipment, string $action, array $before, array $after): void
    {
        $request = app()->bound('request') ? request() : null;

        AuditLog::create([
            'user_id' => Auth::id(),
            'auditable_type' => $shipment->getMorphClass(),
            'auditable_id' => $shipment->getKey(),
            'action' => $action,
            'before' => $before ?: null,
            'after' => $after ?: null,
            'ip' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }

    private function syncAlarm(Shipment $shipment): void
    {
        if ($shipment->endallowdate && $shipment->stillday !== null && $shipment->stillday <= 3 && $shipment->stillday >= 0) {
            Alarm::updateOrCreate(
                ['shipment_id' => $shipment->id],
                [
                    'shipment_name' => $shipment->pillno ?: (string) $shipment->id,
                    'still_days' => $shipment->stillday,
                    'end_date' => $shipment->endallowdate,
                ]
            );
        } else {
            Alarm::where('shipment_id', $shipment->id)->delete();
        }
    }
}
