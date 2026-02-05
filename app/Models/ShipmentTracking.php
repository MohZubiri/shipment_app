<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentTracking extends Model
{
    use HasFactory;

    protected $table = 'shipment_tracking';

    protected $fillable = [
        'shipment_transaction_id',
        'stage_id',
        'event_date',
        'container_count',
        'container_numbers',
        'location',
        'latitude',
        'longitude',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'event_date' => 'datetime',
    ];

    public function shipment()
    {
        return $this->belongsTo(ShipmentTransaction::class, 'shipment_transaction_id');
    }

    public function stage()
    {
        return $this->belongsTo(ShipmentStage::class, 'stage_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
