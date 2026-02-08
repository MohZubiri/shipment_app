<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShipmentStage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'name_en',
        'code',
        'order',
        'icon',
        'color',
        'description',
        'applies_to',
        'is_active',
        'needs_containers',
        'needs_warehouse',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'needs_containers' => 'boolean',
        'needs_warehouse' => 'boolean',
        'order' => 'integer',
    ];

    const DELETED_AT = 'delete_at';

    public function trackingRecords()
    {
        return $this->hasMany(ShipmentTracking::class, 'stage_id');
    }

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'warehouse_stage', 'stage_id', 'warehouse_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeForTransport($query, string $type)
    {
        return $query->whereIn('applies_to', [$type, 'both']);
    }
}
