<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandShippingDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'land_shipping_id',
        'path',
        'original_name',
        'mime_type',
        'size',
    ];

    public function landShipping()
    {
        return $this->belongsTo(LandShipping::class, 'land_shipping_id');
    }
}
