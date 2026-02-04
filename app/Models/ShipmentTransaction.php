<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShipmentTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'shipment_transactions';

    protected $fillable = [
        'operationno',
        'shippmintno',
        'shipgroupno',
        'datano',
        'pillno',
        'pakingno',
        'pilno',
        'orginalno',
        'pillno2',
        'pakingno2',
        'pilno2',
        'orginalno2',
        'paperno',
        'others',
        'shipmtype',
        'departmentno',
        'sectionno',
        'sendingdate',
        'officedate',
        'workerdate',
        'workername',
        'state',
        'dategase',
        'park20',
        'park40',
        'dectype',
        'shippingno',
        'contatty',
        'value',
        'relayname',
        'relaydate',
        'relaycases',
        'alarm',
        'endallowdate',
        'returndate',
        'stillday',
        'customs_port_id',
    ];

    protected $casts = [
        'sendingdate' => 'date',
        'officedate' => 'date',
        'workerdate' => 'date',
        'dategase' => 'date',
        'relaydate' => 'date',
        'endallowdate' => 'date',
        'returndate' => 'date',
        'value' => 'decimal:0',
        'park20' => 'integer',
        'park40' => 'integer',
        'stillday' => 'integer',
    ];

    const DELETED_AT = 'delete_at';

    public function department()
    {
        return $this->belongsTo(Departement::class, 'departmentno');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'sectionno');
    }

    public function shipgroup()
    {
        return $this->belongsTo(Shipgroup::class, 'shipgroupno');
    }

    public function customsData()
    {
        return $this->belongsTo(CustomsData::class, 'datano', 'datano');
    }

    public function shippingLine()
    {
        return $this->belongsTo(ShippingLine::class, 'shippingno');
    }

    public function customsPort()
    {
        return $this->belongsTo(CustomsPort::class, 'customs_port_id');
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shippmintno');
    }

    public function shipmentType()
    {
        return $this->belongsTo(ShipmentType::class, 'shipmtype');
    }

    public function shipmentStatus()
    {
        return $this->belongsTo(ShipmentStatus::class, 'state');
    }

    public function documents()
    {
        return $this->hasMany(ShipmentDocument::class, 'shipment_id');
    }

    public function containers()
    {
        return $this->hasMany(ShipmentContainer::class, 'shipment_transaction_id');
    }

    public function auditLogs()
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }

    public function recalculateAllowances(?Carbon $today = null): void
    {
        $today = $today ?? Carbon::today();

        if (!$this->dategase || !$this->shippingno) {
            $this->endallowdate = null;
            $this->stillday = 0;
            return;
        }

        $allowanceDays = $this->shippingLine?->time
            ?? ShippingLine::query()->whereKey($this->shippingno)->value('time');

        if (!$allowanceDays) {
            $this->endallowdate = null;
            $this->stillday = 0;
            return;
        }

        $end = Carbon::parse($this->dategase)->addDays((int) $allowanceDays);
        $this->endallowdate = $end->toDateString();
        $this->stillday = $today->diffInDays($end, false);
    }
}
