<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipgroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'shipgroup';

    protected $fillable = [
        'name',
        'groupid',
    ];

    const DELETED_AT = 'delete_at';

    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'shipgroupno');
    }
}
