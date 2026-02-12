<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomsData extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'data';

    protected $fillable = [
        'datano',
        'datacreate',
        'state',
    ];

    protected $casts = [
        'datacreate' => 'date',
        'state' => 'integer',
    ];

    const DELETED_AT = 'delete_at';

    public function shipments()
    {
        return $this->hasMany(ShipmentTransaction::class, 'datano', 'datano');
    }

    public function landShipments()
    {
        return $this->hasMany(LandShipping::class, 'declaration_number', 'datano');
    }
}
