<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalShipmentCustoms extends Model
{
    use HasFactory;

    protected $table = 'local_shipment_customs';

    protected $fillable = [
        'local_customs_vehicle_id',
        'customs_port_id',
        'entry_date',
        'entry_time',
        'exit_date',
        'exit_time',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'exit_date' => 'date',
    ];

    public function localCustomsVehicle()
    {
        return $this->belongsTo(LocalCustomsVehicle::class, 'local_customs_vehicle_id');
    }

    public function customsPort()
    {
        return $this->belongsTo(CustomsPort::class, 'customs_port_id');
    }
}
