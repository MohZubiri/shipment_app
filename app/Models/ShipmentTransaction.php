<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShipmentTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'shipment_transactions';

    protected $fillable = [
        'operationno',
        'shippmintno',
        'shipgroupno',
        'datano',
        'pillno',
        'pakingno',
        'pilno',
        'orginalno',
        'pillno2',
        'pakingno2',
        'pilno2',
        'orginalno2',
        'paperno',
        'others',
        'shipmtype',
        'company_id',
        'department_id',
        'sendingdate',
        'officedate',
        'workerdate',
        'workername',
        'state',
        'dategase',
        'park20',
        'park40',
        'dectype',
        'shippingno',
        'contatty',
        'value',
        'relayname',
        'relaydate',
        'relaycases',
        'alarm',
        'endallowdate',
        'returndate',
        'stillday',
        'customs_port_id',
        'current_stage_id',
        'origin_country',
        'origin_port',
        'factory_name',
        'factory_address',
        'manufacturing_date',
        'factory_departure_date',
        'port_departure_date',
        'transit_arrival_date',
        'customs_clearance_date',
        'warehouse_arrival_date',
        'carrier',
        'tracking_number',
        'vessel_name',
        'voyage_number',
        'warehouse_location',
        'warehouse_section',
        'warehouse_zone',
        'shipping_notes',
    ];

    protected $casts = [
        'sendingdate' => 'date',
        'officedate' => 'date',
        'workerdate' => 'date',
        'dategase' => 'date',
        'relaydate' => 'date',
        'endallowdate' => 'date',
        'returndate' => 'date',
        'manufacturing_date' => 'date',
        'factory_departure_date' => 'date',
        'port_departure_date' => 'date',
        'transit_arrival_date' => 'date',
        'customs_clearance_date' => 'date',
        'warehouse_arrival_date' => 'date',
        'value' => 'decimal:0',
        'park20' => 'integer',
        'park40' => 'integer',
        'stillday' => 'integer',
    ];

    const DELETED_AT = 'delete_at';

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function department()
    {
        return $this->belongsTo(Departement::class, 'department_id');
    }

    public function shipgroup()
    {
        return $this->belongsTo(Shipgroup::class, 'shipgroupno');
    }

    public function customsData()
    {
        return $this->belongsTo(CustomsData::class, 'datano', 'datano');
    }

    public function shippingLine()
    {
        return $this->belongsTo(ShippingLine::class, 'shippingno');
    }

    public function customsPort()
    {
        return $this->belongsTo(CustomsPort::class, 'customs_port_id');
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shippmintno');
    }

    public function shipmentType()
    {
        return $this->belongsTo(ShipmentType::class, 'shipmtype');
    }

    public function shipmentStatus()
    {
        return $this->belongsTo(ShipmentStatus::class, 'state');
    }

    public function documents()
    {
        return $this->hasMany(ShipmentDocument::class, 'shipment_id');
    }

    public function containers()
    {
        return $this->hasMany(ShipmentContainer::class, 'shipment_transaction_id');
    }

    public function attachedDocuments()
    {
        return $this->belongsToMany(Document::class, 'document_shipment_transaction', 'shipment_transaction_id', 'document_id')
            ->withTimestamps();
    }

    public function auditLogs()
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }

    public function trackingStageRecords($stage_no)
    {
        return $this->hasMany(ShipmentTracking::class, 'shipment_transaction_id')
           ->where('stage_id', $stage_no) ->orderBy('created_at');
    }

    public function trackingRecords()
    {
        return $this->hasMany(ShipmentTracking::class, 'shipment_transaction_id')
            ->orderBy('created_at');
    }

    public function currentStage()
    {
        return $this->belongsTo(ShipmentStage::class, 'current_stage_id');
    }

    public function addTrackingRecord(array $data): ShipmentTracking
    {
        $tracking = $this->trackingRecords()->create($data);

        // تحديث المرحلة الحالية للشحنة
        $this->update(['current_stage_id' => $tracking->stage_id]);

        return $tracking;
    }

    public function getTotalContainers(): int
    {
        return $this->containers()->sum('container_count');
    }

    public function getUsedContainers($stage_no): int
    {
        return $this->trackingStageRecords($stage_no)
            ->whereNotNull('container_count')
            ->sum('container_count');
    }

    public function warehouseTracking()
    {
        return $this->hasOne(ShipmentTracking::class, 'shipment_transaction_id')
            ->whereHas('stage', function ($q) {
                $q->where('code', 'warehouse');
            })
            ->latest();
    }

    public function getRemainingContainers($stage_no): int
    {
        return $this->getTotalContainers() - $this->getUsedContainers($stage_no);
    }

    public function recalculateAllowances(?Carbon $today = null): void
    {
        $today = $today ?? Carbon::today();

        if (!$this->dategase || !$this->shippingno) {
            $this->endallowdate = null;
            $this->stillday = 0;
            return;
        }

        $allowanceDays = $this->shippingLine?->time
            ?? ShippingLine::query()->whereKey($this->shippingno)->value('time');

        if (!$allowanceDays) {
            $this->endallowdate = null;
            $this->stillday = 0;
            return;
        }

        $end = Carbon::parse($this->dategase)->addDays((int) $allowanceDays);
        $this->endallowdate = $end->toDateString();
        $this->stillday = $today->diffInDays($end, false);
    }
}
