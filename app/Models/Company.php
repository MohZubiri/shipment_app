<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'companies';

    protected $fillable = [
        'name',
    ];

    const DELETED_AT = 'delete_at';

    public function shipments()
    {
        return $this->hasMany(ShipmentTransaction::class, 'company_id');
    }

    public function shipmentTransactions()
    {
        return $this->hasMany(ShipmentTransaction::class, 'company_id');
    }

    public function landShippings()
    {
        return $this->hasMany(LandShipping::class, 'company_id');
    }

    public function localCustomsVehicles()
    {
        return $this->hasMany(LocalCustomsVehicle::class, 'company_id');
    }
}
