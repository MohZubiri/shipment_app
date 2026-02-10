<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShipmentTransaction;
use App\Models\LandShipping;
use App\Models\LocalCustomsVehicle;
use App\Models\Departement;
use App\Models\Section;
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

        if ($request->filled('shipment_name')) {
            $query->where('shippmintno', 'like', '%' . $request->shipment_name . '%');
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

        if ($request->filled('shipment_name')) {
            $query->where('shippmintno', 'like', '%' . $request->shipment_name . '%');
        }

        $shipments = $query->get();

        $selectedDepartment = $request->department_id ? \App\Models\Departement::find($request->department_id) : null;
        $selectedPort = $request->customs_port_id ? \App\Models\CustomsPort::find($request->customs_port_id) : null;

        // Use a landscape layout for better table fit
        $pdf = Pdf::loadView('admin.reports.shipments_pdf', compact('shipments', 'selectedDepartment', 'selectedPort'))
                ->setPaper('a4', 'landscape');

        return $pdf->download('shipment_report_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Land Shipping Report
     */
    public function landShippingReport(Request $request)
    {
        $query = LandShipping::with(['company', 'department', 'currentStage'])
            ->latest('arrival_date');

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('arrival_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('arrival_date', '<=', $request->date_to);
        }

        if ($request->has('company_id') && $request->company_id) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->has('section_id') && $request->section_id) {
            $query->where('section_id', $request->section_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('operation_number', 'like', '%' . $search . '%')
                  ->orWhere('shipment_name', 'like', '%' . $search . '%')
                  ->orWhere('declaration_number', 'like', '%' . $search . '%');
            });
        }

        $landShipments = $query->get();
        $companies = Departement::all();
        $sections = Section::all();

        return view('admin.reports.land_shipping', compact('landShipments', 'companies', 'sections'));
    }

    /**
     * Land Shipping Report PDF
     */
    public function landShippingReportPdf(Request $request)
    {
        $query = LandShipping::with(['company', 'department', 'currentStage'])
            ->latest('arrival_date');

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('arrival_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('arrival_date', '<=', $request->date_to);
        }

        if ($request->has('company_id') && $request->company_id) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->has('section_id') && $request->section_id) {
            $query->where('section_id', $request->section_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('operation_number', 'like', '%' . $search . '%')
                  ->orWhere('shipment_name', 'like', '%' . $search . '%')
                  ->orWhere('declaration_number', 'like', '%' . $search . '%');
            });
        }

        $landShipments = $query->get();

        $selectedCompany = $request->company_id ? Departement::find($request->company_id) : null;
        $selectedSection = $request->section_id ? Section::find($request->section_id) : null;

        $pdf = Pdf::loadView('admin.reports.land_shipping_pdf', compact('landShipments', 'selectedCompany', 'selectedSection'))
                ->setPaper('a4', 'landscape');

        return $pdf->download('land_shipping_report_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Local Customs Vehicles Report
     */
    public function localCustomsReport(Request $request)
    {
        $query = LocalCustomsVehicle::with(['company', 'department'])
            ->latest('arrival_date_from_branch');

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('arrival_date_from_branch', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('arrival_date_from_branch', '<=', $request->date_to);
        }

        if ($request->has('company_id') && $request->company_id) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->has('section_id') && $request->section_id) {
            $query->where('section_id', $request->section_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('serial_number', 'like', '%' . $search . '%')
                  ->orWhere('vehicle_plate_number', 'like', '%' . $search . '%')
                  ->orWhere('user_name', 'like', '%' . $search . '%');
            });
        }

        $vehicles = $query->get();
        $companies = Departement::all();
        $sections = Section::all();

        return view('admin.reports.local_customs', compact('vehicles', 'companies', 'sections'));
    }

    /**
     * Local Customs Vehicles Report PDF
     */
    public function localCustomsReportPdf(Request $request)
    {
        $query = LocalCustomsVehicle::with(['company', 'department'])
            ->latest('arrival_date_from_branch');

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('arrival_date_from_branch', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('arrival_date_from_branch', '<=', $request->date_to);
        }

        if ($request->has('company_id') && $request->company_id) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->has('section_id') && $request->section_id) {
            $query->where('section_id', $request->section_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('serial_number', 'like', '%' . $search . '%')
                  ->orWhere('vehicle_plate_number', 'like', '%' . $search . '%')
                  ->orWhere('user_name', 'like', '%' . $search . '%');
            });
        }

        $vehicles = $query->get();

        $selectedCompany = $request->company_id ? Departement::find($request->company_id) : null;
        $selectedSection = $request->section_id ? Section::find($request->section_id) : null;

        $pdf = Pdf::loadView('admin.reports.local_customs_pdf', compact('vehicles', 'selectedCompany', 'selectedSection'))
                ->setPaper('a4', 'landscape');

        return $pdf->download('local_customs_report_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Summary Report by Company
     */
    public function summaryReport(Request $request)
    {
        $query = Departement::withCount([
            'shipmentTransactions',
            'landShippings',
            'localCustomsVehicles'
        ]);

        if ($request->has('company_id') && $request->company_id) {
            $query->where('id', $request->company_id);
        }

        $companies = $query->get();

        // Get all companies for filter dropdown
        $allCompanies = Departement::all();

        return view('admin.reports.summary', compact('companies', 'allCompanies'));
    }

    /**
     * Summary Report PDF
     */
    public function summaryReportPdf(Request $request)
    {
        $query = Departement::withCount([
            'shipmentTransactions',
            'landShippings',
            'localCustomsVehicles'
        ]);

        if ($request->has('company_id') && $request->company_id) {
            $query->where('id', $request->company_id);
        }

        $companies = $query->get();

        $selectedCompany = $request->company_id ? Departement::find($request->company_id) : null;

        $pdf = Pdf::loadView('admin.reports.summary_pdf', compact('companies', 'selectedCompany'))
                ->setPaper('a4', 'landscape');

        return $pdf->download('summary_report_' . now()->format('Y-m-d') . '.pdf');
    }
}
