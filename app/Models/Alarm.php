<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alarm extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'shipment_name',
        'still_days',
        'end_date',
    ];

    protected $casts = [
        'end_date' => 'date',
        'still_days' => 'integer',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
}
