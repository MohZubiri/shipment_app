<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Departement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'departement';

    protected $fillable = [
        'name',
    ];

    const DELETED_AT = 'delete_at';

    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'departmentno');
    }
}
