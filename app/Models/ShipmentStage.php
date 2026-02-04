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
        'is_active',
        'needs_containers',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'needs_containers' => 'boolean',
        'order' => 'integer',
    ];

    const DELETED_AT = 'delete_at';

    public function trackingRecords()
    {
        return $this->hasMany(ShipmentTracking::class, 'stage_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
