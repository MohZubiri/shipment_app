<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShipmentTransaction;
use App\Models\LandShipping;
use App\Models\LocalCustomsVehicle;
use App\Models\Company;
use App\Models\Departement;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view reports')->only(['index']);
        $this->middleware('permission:view shipments report')->only(['shipmentReport', 'shipmentReportPdf']);
        $this->middleware('permission:view land shipping report')->only(['landShippingReport', 'landShippingReportPdf']);
        $this->middleware('permission:view local customs report')->only(['localCustomsReport', 'localCustomsReportPdf']);
        $this->middleware('permission:view summary report')->only(['summaryReport', 'summaryReportPdf']);
    }

    public function index()
    {
        return view('admin.reports.index');
    }

    public function shipmentReport(Request $request)
    {
        $query = ShipmentTransaction::with(['shippingLine', 'customsPort', 'company', 'department', 'shipgroup', 'containers', 'attachedDocuments', 'currentStage', 'warehouseTracking.warehouse'])
            ->latest('sendingdate');

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('sendingdate', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('sendingdate', '<=', $request->date_to);
        }

        if ($request->has('company_id') && $request->company_id) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->has('customs_port_id') && $request->customs_port_id) {
            $query->where('customs_port_id', $request->customs_port_id);
        }

        if ($request->has('department_id') && $request->department_id) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('shipment_name')) {
            $query->where('shippmintno', 'like', '%' . $request->shipment_name . '%');
        }

        if ($request->filled('shipping_line_type')) {
            $shippingLineType = $request->shipping_line_type;
            $query->whereHas('shippingLine', function ($q) use ($shippingLineType) {
                $q->where(function ($inner) use ($shippingLineType) {
                    $inner->where('transport_type', $shippingLineType);
                    if ($shippingLineType === 'sea') {
                        $inner->orWhereNull('transport_type');
                    }
                });
            });
        }

        $hasFilters = $request->filled('date_from') || $request->filled('date_to') ||
                      $request->filled('company_id') || $request->filled('customs_port_id') ||
                      $request->filled('department_id') || $request->filled('shipping_line_type');

        if ($hasFilters) {
            $shipments = $query->get();
        } else {
            $shipments = collect();
        }
        
        $companies = Company::all();
        $departments = Departement::all();
        $ports = \App\Models\CustomsPort::all();

        return view('admin.reports.shipments', compact('shipments', 'companies', 'departments', 'ports', 'hasFilters'));
    }

    public function shipmentReportPdf(Request $request)
    {
        $query = ShipmentTransaction::with(['shippingLine', 'customsPort', 'company', 'department', 'shipgroup', 'containers', 'attachedDocuments', 'currentStage', 'warehouseTracking.warehouse'])
            ->latest('sendingdate');

         if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('sendingdate', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
             $query->whereDate('sendingdate', '<=', $request->date_to);
        }

        if ($request->has('company_id') && $request->company_id) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->has('customs_port_id') && $request->customs_port_id) {
            $query->where('customs_port_id', $request->customs_port_id);
        }

        if ($request->has('department_id') && $request->department_id) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('shipment_name')) {
            $query->where('shippmintno', 'like', '%' . $request->shipment_name . '%');
        }

        if ($request->filled('shipping_line_type')) {
            $shippingLineType = $request->shipping_line_type;
            $query->whereHas('shippingLine', function ($q) use ($shippingLineType) {
                $q->where(function ($inner) use ($shippingLineType) {
                    $inner->where('transport_type', $shippingLineType);
                    if ($shippingLineType === 'sea') {
                        $inner->orWhereNull('transport_type');
                    }
                });
            });
        }

        $shipments = $query->get();

        $selectedCompany = $request->company_id ? Company::find($request->company_id) : null;
        $selectedDepartment = $request->department_id ? Departement::find($request->department_id) : null;
        $selectedPort = $request->customs_port_id ? \App\Models\CustomsPort::find($request->customs_port_id) : null;
        $selectedShippingLineType = $request->shipping_line_type ? match ($request->shipping_line_type) {
            'sea' => 'بحري',
            'air' => 'جوي',
            'land' => 'بري',
            default => $request->shipping_line_type,
        } : null;
        $selectedFont = $request->font ?? 'cairo';

        // Define font data with fonts from storage/fonts directory
        $fontData = [
            'cairo' => [
                'R' => 'Cairo-Regular.ttf',
                'B' => 'Cairo-Bold.ttf',
                'I' => 'Cairo-Regular.ttf',
                'BI' => 'Cairo-Bold.ttf',
                'useOTL' => 0xFF,
                'useKashida' => 75,
            ],
            'almarai' => [
                'R' => 'Almarai-Regular.ttf',
                'B' => 'Almarai-Bold.ttf',
                'I' => 'Almarai-Regular.ttf',
                'BI' => 'Almarai-Bold.ttf',
                'useOTL' => 0xFF,
                'useKashida' => 75,
            ],
        ];

        // Use a landscape layout for better table fit
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-l',
            'orientation' => 'L', // Changed to 'L' for landscape
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'fontDir' => [storage_path('fonts')],
            'fontdata' => $fontData,
            'default_font' => $selectedFont,
        ]);
        $mpdf->SetDirectionality('rtl');
        $html = view('admin.reports.shipments_pdf', compact('shipments', 'selectedCompany', 'selectedDepartment', 'selectedPort', 'selectedShippingLineType'))->render();
        $mpdf->WriteHTML($html);
        return response($mpdf->Output('shipment_report_' . now()->format('Y-m-d') . '.pdf', 'D'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="shipment_report_' . now()->format('Y-m-d') . '.pdf"');
    }

    /**
     * Land Shipping Report
     */
    public function landShippingReport(Request $request)
    {
        $query = LandShipping::with(['company', 'department', 'currentStage', 'warehouseTracking.warehouse', 'attachedDocuments', 'locomotives', 'customsData'])
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

        if ($request->has('department_id') && $request->department_id) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->has('customs_port_id') && $request->customs_port_id) {
            $query->where('customs_port_id', $request->customs_port_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('operation_number', 'like', '%' . $search . '%')
                  ->orWhere('shipment_name', 'like', '%' . $search . '%')
                  ->orWhere('declaration_number', 'like', '%' . $search . '%');
            });
        }

        $hasFilters = $request->filled('date_from') || $request->filled('date_to') ||
                      $request->filled('company_id') || $request->filled('department_id') ||
                      $request->filled('customs_port_id') || $request->filled('search');

        if ($hasFilters) {
            $landShipments = $query->get();
        } else {
            $landShipments = collect();
        }

        $companies = Company::all();
        $departments = Departement::all();
        $ports = \App\Models\CustomsPort::all();

        return view('admin.reports.land_shipping', compact('landShipments', 'companies', 'departments', 'ports', 'hasFilters'));
    }

    /**
     * Land Shipping Report PDF
     */
    public function landShippingReportPdf(Request $request)
    {
        $query = LandShipping::with(['company', 'department', 'currentStage', 'warehouseTracking.warehouse', 'attachedDocuments', 'locomotives', 'customsData'])
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

        if ($request->has('department_id') && $request->department_id) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->has('customs_port_id') && $request->customs_port_id) {
            $query->where('customs_port_id', $request->customs_port_id);
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

        $selectedCompany = $request->company_id ? Company::find($request->company_id) : null;
        $selectedDepartment = $request->department_id ? Departement::find($request->department_id) : null;
        $selectedPort = $request->customs_port_id ? \App\Models\CustomsPort::find($request->customs_port_id) : null;
        $selectedFont = $request->font ?? 'cairo';

        // Define font data with fonts from storage/fonts directory
        $fontData = [
            'cairo' => [
                'R' => 'Cairo-Regular.ttf',
                'B' => 'Cairo-Bold.ttf',
                'I' => 'Cairo-Regular.ttf',
                'BI' => 'Cairo-Bold.ttf',
                'useOTL' => 0xFF,
                'useKashida' => 75,
            ],
            'almarai' => [
                'R' => 'Almarai-Regular.ttf',
                'B' => 'Almarai-Bold.ttf',
                'I' => 'Almarai-Regular.ttf',
                'BI' => 'Almarai-Bold.ttf',
                'useOTL' => 0xFF,
                'useKashida' => 75,
            ],
        ];

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'L', // Changed to 'L' for landscape
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'fontDir' => [storage_path('fonts')],
            'fontdata' => $fontData,
            'default_font' => $selectedFont,
        ]);
        $mpdf->SetDirectionality('rtl');
        $html = view('admin.reports.land_shipping_pdf', compact('landShipments', 'selectedCompany', 'selectedDepartment', 'selectedPort'))->render();
        $mpdf->WriteHTML($html);
        return response($mpdf->Output('land_shipping_report_' . now()->format('Y-m-d') . '.pdf', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="land_shipping_report_' . now()->format('Y-m-d') . '.pdf"');
    }

    /**
     * Local Customs Vehicles Report
     */
    public function localCustomsReport(Request $request)
    {
        $query = LocalCustomsVehicle::with(['company', 'department', 'warehouse', 'customs.customsPort'])
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

        if ($request->has('department_id') && $request->department_id) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->has('customs_port_id') && $request->customs_port_id) {
            $query->whereHas('customs', function ($q) use ($request) {
                $q->where('customs_port_id', $request->customs_port_id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('serial_number', 'like', '%' . $search . '%')
                  ->orWhere('vehicle_plate_number', 'like', '%' . $search . '%')
                  ->orWhere('driver_name', 'like', '%' . $search . '%');
            });
        }

        $hasFilters = $request->filled('date_from') || $request->filled('date_to') ||
                      $request->filled('company_id') || $request->filled('department_id') ||
                      $request->filled('customs_port_id') || $request->filled('search');

        if ($hasFilters) {
            $vehicles = $query->get();
        } else {
            $vehicles = collect();
        }

        $companies = Company::all();
        $departments = Departement::all();
        $ports = \App\Models\CustomsPort::all();

        return view('admin.reports.local_customs', compact('vehicles', 'companies', 'departments', 'ports', 'hasFilters'));
    }

    /**
     * Local Customs Vehicles Report PDF
     */
    public function localCustomsReportPdf(Request $request)
    {
        $query = LocalCustomsVehicle::with(['company', 'department', 'warehouse', 'customs.customsPort'])
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

        if ($request->has('department_id') && $request->department_id) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->has('customs_port_id') && $request->customs_port_id) {
            $query->whereHas('customs', function ($q) use ($request) {
                $q->where('customs_port_id', $request->customs_port_id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('serial_number', 'like', '%' . $search . '%')
                  ->orWhere('vehicle_plate_number', 'like', '%' . $search . '%')
                  ->orWhere('driver_name', 'like', '%' . $search . '%');
            });
        }

        $vehicles = $query->get();

        $selectedCompany = $request->company_id ? Company::find($request->company_id) : null;
        $selectedDepartment = $request->department_id ? Departement::find($request->department_id) : null;
        $selectedPort = $request->customs_port_id ? \App\Models\CustomsPort::find($request->customs_port_id) : null;
        $selectedFont = $request->font ?? 'cairo';

        // Define font data with fonts from storage/fonts directory
        $fontData = [
            'cairo' => [
                'R' => 'Cairo-Regular.ttf',
                'B' => 'Cairo-Bold.ttf',
                'I' => 'Cairo-Regular.ttf',
                'BI' => 'Cairo-Bold.ttf',
                'useOTL' => 0xFF,
                'useKashida' => 75,
            ],
            'almarai' => [
                'R' => 'Almarai-Regular.ttf',
                'B' => 'Almarai-Bold.ttf',
                'I' => 'Almarai-Regular.ttf',
                'BI' => 'Almarai-Bold.ttf',
                'useOTL' => 0xFF,
                'useKashida' => 75,
            ],
        ];

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'L', // Changed to 'L' for landscape
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'fontDir' => [storage_path('fonts')],
            'fontdata' => $fontData,
            'default_font' => $selectedFont,
        ]);
        $mpdf->SetDirectionality('rtl');
        $html = view('admin.reports.local_customs_pdf', compact('vehicles', 'selectedCompany', 'selectedDepartment', 'selectedPort'))->render();
        $mpdf->WriteHTML($html);
        return response($mpdf->Output('local_customs_report_' . now()->format('Y-m-d') . '.pdf', 'D'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="local_customs_report_' . now()->format('Y-m-d') . '.pdf"');
    }

    /**
     * Summary Report by Company
     */
    public function summaryReport(Request $request)
    {
        $query = Company::withCount([
            'shipmentTransactions',
            'landShippings',
            'localCustomsVehicles'
        ]);

        if ($request->has('company_id') && $request->company_id) {
            $query->where('id', $request->company_id);
        }

        $companies = $query->get();

        // Get all companies for filter dropdown
        $allCompanies = Company::all();

        return view('admin.reports.summary', compact('companies', 'allCompanies'));
    }

    /**
     * Summary Report PDF
     */
    public function summaryReportPdf(Request $request)
    {
        $query = Company::withCount([
            'shipmentTransactions',
            'landShippings',
            'localCustomsVehicles'
        ]);

        if ($request->has('company_id') && $request->company_id) {
            $query->where('id', $request->company_id);
        }

        $companies = $query->get();

        $selectedCompany = $request->company_id ? Company::find($request->company_id) : null;
        $selectedFont = $request->font ?? 'cairo';

        // Define font data with fonts from storage/fonts directory
        $fontData = [
            'cairo' => [
                'R' => 'Cairo-Regular.ttf',
                'B' => 'Cairo-Bold.ttf',
                'I' => 'Cairo-Regular.ttf',
                'BI' => 'Cairo-Bold.ttf',
                'useOTL' => 0xFF,
                'useKashida' => 75,
            ],
            'almarai' => [
                'R' => 'Almarai-Regular.ttf',
                'B' => 'Almarai-Bold.ttf',
                'I' => 'Almarai-Regular.ttf',
                'BI' => 'Almarai-Bold.ttf',
                'useOTL' => 0xFF,
                'useKashida' => 75,
            ],
        ];

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'L', // Changed to 'L' for landscape
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'fontDir' => [storage_path('fonts')],
            'fontdata' => $fontData,
            'default_font' => $selectedFont,
        ]);
        $mpdf->SetDirectionality('rtl');
        $html = view('admin.reports.summary_pdf', compact('companies', 'selectedCompany'))->render();
        $mpdf->WriteHTML($html);
        return response($mpdf->Output('summary_report_' . now()->format('Y-m-d') . '.pdf', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="summary_report_' . now()->format('Y-m-d') . '.pdf"');
    }
}
