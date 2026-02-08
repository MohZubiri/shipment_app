<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Departement;
use App\Models\Section;
use App\Models\ShipmentStage;

class LandShipping extends Model
{
    use HasFactory;

    protected $table = 'land_shipping';

    protected $fillable = [
        'operation_number',
        'locomotive_number',
        'shipment_name',
        'declaration_number',
        'arrival_date',
        'exit_date',
        'docking_days',
        'documents_sent_date',
        'documents_type',
        'warehouse_arrival_date',
        'company_id',
        'section_id',
        'current_stage_id',
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
        return $this->belongsTo(Departement::class, 'company_id');
    }

    public function department()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function documents()
    {
        return $this->hasMany(LandShippingDocument::class, 'land_shipping_id');
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

    public function addTrackingRecord(array $data): LandShippingTracking
    {
        $tracking = $this->trackingRecords()->create($data);
        $this->update(['current_stage_id' => $tracking->stage_id]);

        return $tracking;
    }
}
