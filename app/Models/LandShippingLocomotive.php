<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandShippingLocomotive extends Model
{
    use HasFactory;

    protected $fillable = ['land_shipping_id', 'locomotive_number'];

    public function landShipping()
    {
        return $this->belongsTo(LandShipping::class);
    }
}
