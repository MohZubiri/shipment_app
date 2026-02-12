<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Company;
use App\Models\Departement;
use App\Models\ShipmentStage;
use App\Models\CustomsPort;
use App\Models\CustomsData;

class LandShipping extends Model
{
    use HasFactory;

    protected $table = 'land_shipping';

    protected $fillable = [
        'operation_number',
        'shipment_name',
        'declaration_number',
        'arrival_date',
        'exit_date',
        'docking_days',
        'documents_sent_date',
        'documents_type',
        'warehouse_arrival_date',
        'company_id',
        'department_id',
        'current_stage_id',
        'customs_port_id',
    ];

    protected $casts = [
        'arrival_date' => 'date',
        'exit_date' => 'date',
        'documents_sent_date' => 'date',
        'warehouse_arrival_date' => 'date',
        'docking_days' => 'integer',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function department()
    {
        return $this->belongsTo(Departement::class, 'department_id');
    }

    public function customsPort()
    {
        return $this->belongsTo(CustomsPort::class, 'customs_port_id');
    }

    public function customsData()
    {
        return $this->belongsTo(CustomsData::class, 'declaration_number', 'datano');
    }

    public function locomotives()
    {
        return $this->hasMany(LandShippingLocomotive::class);
    }

    public function documents()
    {
        return $this->hasMany(LandShippingDocument::class, 'land_shipping_id');
    }

    public function attachedDocuments()
    {
        return $this->belongsToMany(Document::class, 'document_land_shipping', 'land_shipping_id', 'document_id')
            ->withTimestamps();
    }

    public function trackingStageRecords($stageId)
    {
        return $this->hasMany(LandShippingTracking::class, 'land_shipping_id')
            ->where('stage_id', $stageId)
            ->orderBy('created_at');
    }

    public function trackingRecords()
    {
        return $this->hasMany(LandShippingTracking::class, 'land_shipping_id')
            ->orderBy('created_at');
    }

    public function currentStage()
    {
        return $this->belongsTo(ShipmentStage::class, 'current_stage_id');
    }

    public function warehouseTracking()
    {
        return $this->hasOne(LandShippingTracking::class, 'land_shipping_id')
            ->whereHas('stage', function ($q) {
                $q->where('code', 'warehouse');
            })
            ->latest('event_date');
    }

    public function addTrackingRecord(array $data): LandShippingTracking
    {
        $tracking = $this->trackingRecords()->create($data);
        $this->update(['current_stage_id' => $tracking->stage_id]);

        return $tracking;
    }
}
