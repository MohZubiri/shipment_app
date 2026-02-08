<?php

namespace App\Http\Controllers;

use App\Models\Alarm;
use App\Models\Departement;
use App\Models\ShipmentStage;
use App\Models\ShipmentTransaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $totalActive = ShipmentTransaction::query()
            ->whereNull('returndate')
            ->count();

        $portArrivalStageId = ShipmentStage::query()
            ->where('code', 'port_arrival')
            ->orWhere('name', 'وصول الميناء')
            ->value('id');

        $pendingRelease = $portArrivalStageId
            ? ShipmentTransaction::query()->where('current_stage_id', $portArrivalStageId)->count()
            : 0;

        $nearExpiryCount = ShipmentTransaction::query()
            ->whereNotNull('dategase')
            ->whereBetween('stillday', [0, 3])
            ->count();

        $completedToday = ShipmentTransaction::query()
            ->whereDate('returndate', $today)
            ->count();

        $alerts = Alarm::query()
            ->with('shipment.department')
            ->orderBy('still_days')
            ->limit(6)
            ->get();

        $byDepartment = Departement::query()
            ->withCount(['shipments as shipments_count' => function ($query) {
                $query->whereNotNull('dategase');
            }])
            ->orderBy('name')
            ->get();

        $maxDepartmentCount = $byDepartment->max('shipments_count') ?: 1;

        return view('dashboard', [
            'today' => $today,
            'totalActive' => $totalActive,
            'pendingRelease' => $pendingRelease,
            'nearExpiryCount' => $nearExpiryCount,
            'completedToday' => $completedToday,
            'byDepartment' => $byDepartment,
            'maxDepartmentCount' => $maxDepartmentCount,
            'alerts' => $alerts,
        ]);
    }
}
