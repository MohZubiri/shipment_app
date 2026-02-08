<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Departement;
use App\Models\Section;

class LocalCustomsVehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_number',
        'vehicle_plate_number',
        'user_name',
        'arrival_time_from_branch',
        'departure_time_to_branch',
        'arrival_date_from_branch',
        'destination',
        'cargo_type',
        'cargo_description',
        'vehicle_number',
        'manufacture_date',
        'exit_date_from_manufacture',
        'notes',
        'created_by',
        'is_active',
        'company_id',
        'section_id',
    ];

    protected $casts = [
        'arrival_date_from_branch' => 'date',
        'manufacture_date' => 'date',
        'exit_date_from_manufacture' => 'date',
        'is_active' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Departement::class, 'company_id');
    }

    public function department()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
}
