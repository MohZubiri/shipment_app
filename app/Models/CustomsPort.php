<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomsPort extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customs_port';

    protected $fillable = [
        'name',
        'code',
        'type',
        'city',
        'country',
    ];

    const DELETED_AT = 'delete_at';

    public function shipments()
    {
        return $this->hasMany(ShipmentTransaction::class, 'customs_port_id');
    }
}
