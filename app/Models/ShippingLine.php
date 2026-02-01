<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingLine extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'shipping_line';

    protected $fillable = [
        'name',
        'company_name',
        'time',
        'code',
        'contact_email',
        'phone',
    ];

    protected $casts = [
        'time' => 'integer',
    ];

    const DELETED_AT = 'delete_at';

    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'shippingno');
    }
}
