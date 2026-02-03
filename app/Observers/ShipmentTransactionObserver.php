<?php

namespace App\Observers;

use App\Models\Alarm;
use App\Models\AuditLog;
use App\Models\ShipmentTransaction;
use Illuminate\Support\Facades\Auth;

class ShipmentTransactionObserver
{
    /**
     * Handle the ShipmentTransaction "saving" event.
     */
    public function saving(ShipmentTransaction $shipment): void
    {
        $shipment->recalculateAllowances();
    }

    /**
     * Handle the ShipmentTransaction "created" event.
     */
    public function created(ShipmentTransaction $shipment): void
    {
        $this->logChange($shipment, 'created', [], $shipment->getAttributes());
    }

    /**
     * Handle the ShipmentTransaction "updated" event.
     */
    public function updated(ShipmentTransaction $shipment): void
    {
        $changes = $shipment->getChanges();
        $before = array_intersect_key($shipment->getOriginal(), $changes);

        $this->logChange($shipment, 'updated', $before, $changes);
    }

    /**
     * Handle the ShipmentTransaction "deleted" event.
     */
    public function deleted(ShipmentTransaction $shipment): void
    {
        //
    }

    /**
     * Handle the ShipmentTransaction "restored" event.
     */
    public function restored(ShipmentTransaction $shipment): void
    {
        //
    }

    /**
     * Handle the ShipmentTransaction "force deleted" event.
     */
    public function forceDeleted(ShipmentTransaction $shipment): void
    {
        //
    }

    /**
     * Handle the ShipmentTransaction "saved" event.
     */
    public function saved(ShipmentTransaction $shipment): void
    {
        $this->syncAlarm($shipment);
    }

    private function logChange(ShipmentTransaction $shipment, string $action, array $before, array $after): void
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

    private function syncAlarm(ShipmentTransaction $shipment): void
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
