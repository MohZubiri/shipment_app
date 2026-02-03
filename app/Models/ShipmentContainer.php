<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentContainer extends Model
{
    use HasFactory;

    protected $table = 'shipment_containers';

    protected $fillable = [
        'shipment_transaction_id',
        'invoice_number',
        'packing_list_number',
        'certificate_of_origin',
        'bill_of_lading',
        'container_count',
        'container_size',
    ];

    protected $casts = [
        'container_count' => 'integer',
    ];

    public function shipmentTransaction()
    {
        return $this->belongsTo(ShipmentTransaction::class);
    }
}
