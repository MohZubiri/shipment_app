<?php

namespace App\Http\Controllers;

use App\Models\Alarm;
use App\Models\Departement;
use App\Models\Shipment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $totalActive = Shipment::query()
            ->whereNull('returndate')
            ->count();

        $pendingRelease = Shipment::query()
            ->whereNotNull('dategase')
            ->whereNull('relaydate')
            ->count();

        $nearExpiryCount = Shipment::query()
            ->whereNotNull('dategase')
            ->whereBetween('stillday', [0, 3])
            ->count();

        $completedToday = Shipment::query()
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
