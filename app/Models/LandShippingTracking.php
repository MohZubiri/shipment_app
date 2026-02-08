<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandShippingTracking extends Model
{
    use HasFactory;

    protected $table = 'land_shipping_tracking';

    protected $fillable = [
        'land_shipping_id',
        'stage_id',
        'warehouse_id',
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

    public function landShipping()
    {
        return $this->belongsTo(LandShipping::class, 'land_shipping_id');
    }

    public function stage()
    {
        return $this->belongsTo(ShipmentStage::class, 'stage_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
