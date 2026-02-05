<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'location',
        'address',
        'capacity',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'capacity' => 'integer',
    ];

    public function stages()
    {
        return $this->belongsToMany(ShipmentStage::class, 'warehouse_stage', 'warehouse_id', 'stage_id');
    }

    public function trackingRecords()
    {
        return $this->hasMany(ShipmentTracking::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
