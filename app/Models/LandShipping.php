<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandShipping extends Model
{
    use HasFactory;

    protected $table = 'shipping_reports';

    protected $fillable = [
        'operation_number',
        'locomotive_number',
        'shipment_name',
        'declaration_number',
        'arrival_date',
        'exit_date',
        'docking_days',
        'documents_sent_date',
        'documents_type',
        'warehouse_arrival_date',
    ];

    protected $casts = [
        'arrival_date' => 'date',
        'exit_date' => 'date',
        'documents_sent_date' => 'date',
        'warehouse_arrival_date' => 'date',
        'docking_days' => 'integer',
    ];
}
