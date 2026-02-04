<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShipmentTransaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function shipmentReport(Request $request)
    {
        $query = ShipmentTransaction::with(['shippingLine', 'customsPort', 'department', 'section', 'shipgroup'])
            ->latest('sendingdate');

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('sendingdate', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('sendingdate', '<=', $request->date_to);
        }

        if ($request->has('department_id') && $request->department_id) {
            $query->where('departmentno', $request->department_id);
        }

        if ($request->has('customs_port_id') && $request->customs_port_id) {
            $query->where('customs_port_id', $request->customs_port_id);
        }

        $shipments = $query->get();
        $departments = \App\Models\Departement::all();
        $ports = \App\Models\CustomsPort::all();

        return view('admin.reports.shipments', compact('shipments', 'departments', 'ports'));
    }

    public function shipmentReportPdf(Request $request)
    {
        $query = ShipmentTransaction::with(['shippingLine', 'customsPort', 'department', 'section', 'shipgroup'])
            ->latest('sendingdate');

         if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('sendingdate', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
             $query->whereDate('sendingdate', '<=', $request->date_to);
        }

        if ($request->has('department_id') && $request->department_id) {
            $query->where('departmentno', $request->department_id);
        }

        if ($request->has('customs_port_id') && $request->customs_port_id) {
            $query->where('customs_port_id', $request->customs_port_id);
        }

        $shipments = $query->get();

        $selectedDepartment = $request->department_id ? \App\Models\Departement::find($request->department_id) : null;
        $selectedPort = $request->customs_port_id ? \App\Models\CustomsPort::find($request->customs_port_id) : null;

        // Use a landscape layout for better table fit
        $pdf = Pdf::loadView('admin.reports.shipments_pdf', compact('shipments', 'selectedDepartment', 'selectedPort'))
                ->setPaper('a4', 'landscape');

        return $pdf->download('shipment_report_' . now()->format('Y-m-d') . '.pdf');
    }
}
