<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CustomsData;
use App\Models\LandShipping;
use App\Models\LandShippingDocument;
use App\Models\Document;
use App\Models\Departement;
use App\Models\CustomsPort;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandShippingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view land shipments')->only(['index', 'show', 'downloadDocument']);
        $this->middleware('permission:create land shipments')->only(['create', 'store']);
        $this->middleware('permission:edit land shipments')->only(['edit', 'update']);
        $this->middleware('permission:delete land shipments')->only(['destroy']);
        $this->middleware('permission:export land shipments')->only(['export']);
    }

    public function index(Request $request)
    {
        $query = LandShipping::query()->with([
            'company',
            'department',
            'documents',
            'currentStage',
            'locomotives',
            'customsPort',
            'customsData',
            'warehouseTracking.warehouse',
        ]);

        if ($request->filled('search')) {
            $search = trim((string) $request->get('search'));
            $query->where(function ($builder) use ($search) {
                $builder->where('operation_number', 'like', "%{$search}%")
                    ->orWhere('shipment_name', 'like', "%{$search}%")
                    ->orWhere('declaration_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('arrival_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('arrival_date', '<=', $request->date_to);
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Default sort: ascending by operation number for clarity
        $reports = $query->orderBy('operation_number')->paginate(15)->withQueryString();

        $companies = Company::query()->orderBy('name')->get();
        $departments = Departement::query()->orderBy('name')->get();

        return view('shipping_reports.index', compact('reports', 'companies', 'departments'));
    }

    public function create()
    {
        return view('shipping_reports.create', [
            'companies' => Company::query()->orderBy('name')->get(),
            'departments' => Departement::query()->orderBy('name')->get(),
            'customsDataList' => CustomsData::query()->orderByDesc('datano')->pluck('datano'),
            'activeDocuments' => Document::query()->where('is_active', true)->orderBy('name')->get(),
            'customsPorts' => CustomsPort::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'operation_number' => ['required', 'string', 'max:50'],
            'locomotive_numbers' => ['nullable', 'array'],
            'locomotive_numbers.*' => ['nullable', 'string', 'max:50'],
            'shipment_name' => ['nullable', 'string', 'max:200'],
            'declaration_number' => ['nullable', 'integer', 'min:1', 'exists:data,datano'],
            'arrival_date' => ['nullable', 'date'],
            'exit_date' => ['nullable', 'date'],
            'documents_type' => ['nullable', 'string', 'max:100'],
            'documents_sent_date' => ['nullable', 'date'],
            'company_id' => ['required', 'exists:companies,id'],
            'department_id' => ['nullable', 'exists:departements,id'],
            'customs_port_id' => ['nullable', 'exists:customs_port,id'],
            'documents_zip' => ['nullable', 'array'],
            'documents_zip.*' => ['file', 'mimes:zip,pdf', 'max:51200'],
            'attached_documents' => ['nullable', 'array'],
            'attached_documents.*' => ['integer'],
        ]);

        $attachedDocuments = $data['attached_documents'] ?? [];
        unset($data['documents_zip'], $data['attached_documents'], $data['locomotive_numbers']);

        $landShipping = LandShipping::create($data);

        if ($request->filled('locomotive_numbers')) {
            foreach ($request->locomotive_numbers as $number) {
                if (!empty($number)) {
                    $landShipping->locomotives()->create([
                        'locomotive_number' => $number,
                    ]);
                }
            }
        }

        if (!empty($attachedDocuments)) {
            $landShipping->attachedDocuments()->sync($attachedDocuments);
        }

        if ($request->hasFile('documents_zip')) {
            $files = $request->file('documents_zip');
            $files = is_array($files) ? $files : [$files];

            foreach ($files as $file) {
                $path = $file->store("land_shipping_documents/{$landShipping->id}", 'public');

                LandShippingDocument::create([
                    'land_shipping_id' => $landShipping->id,
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        return redirect()
            ->route('road-shipments.index')
            ->with('status', 'تم إضافة الشحنة الدولية البرية بنجاح.');
    }

    public function edit(LandShipping $landShipping)
    {
        $landShipping->load(['locomotives', 'attachedDocuments', 'documents']);

        return view('shipping_reports.edit', [
            'landShipping' => $landShipping,
            'companies' => Company::query()->orderBy('name')->get(),
            'departments' => Departement::query()->orderBy('name')->get(),
            'customsDataList' => CustomsData::query()->orderByDesc('datano')->pluck('datano'),
            'activeDocuments' => Document::query()->where('is_active', true)->orderBy('name')->get(),
            'customsPorts' => CustomsPort::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, LandShipping $landShipping)
    {
        $data = $request->validate([
            'operation_number' => ['required', 'string', 'max:50'],
            'locomotive_numbers' => ['nullable', 'array'],
            'locomotive_numbers.*' => ['nullable', 'string', 'max:50'],
            'shipment_name' => ['nullable', 'string', 'max:200'],
            'declaration_number' => ['nullable', 'integer', 'min:1', 'exists:data,datano'],
            'arrival_date' => ['nullable', 'date'],
            'exit_date' => ['nullable', 'date'],
            'documents_type' => ['nullable', 'string', 'max:100'],
            'documents_sent_date' => ['nullable', 'date'],
            'company_id' => ['required', 'exists:companies,id'],
            'department_id' => ['nullable', 'exists:departements,id'],
            'customs_port_id' => ['nullable', 'exists:customs_port,id'],
            'documents_zip' => ['nullable', 'array'],
            'documents_zip.*' => ['file', 'mimes:zip,pdf', 'max:51200'],
            'attached_documents' => ['nullable', 'array'],
            'attached_documents.*' => ['integer'],
            'documents_to_delete' => ['nullable', 'array'],
            'documents_to_delete.*' => ['integer', 'exists:land_shipping_documents,id'],
        ]);

        $attachedDocuments = $data['attached_documents'] ?? [];
        $documentsToDelete = $data['documents_to_delete'] ?? [];
        unset($data['documents_zip'], $data['attached_documents'], $data['documents_to_delete'], $data['locomotive_numbers']);

        $landShipping->update($data);

        // Sync locomotives
        $landShipping->locomotives()->delete();
        if ($request->filled('locomotive_numbers')) {
            foreach ($request->locomotive_numbers as $number) {
                if (!empty($number)) {
                    $landShipping->locomotives()->create([
                        'locomotive_number' => $number,
                    ]);
                }
            }
        }

        if (!empty($attachedDocuments)) {
            $landShipping->attachedDocuments()->sync($attachedDocuments);
        } else {
            $landShipping->attachedDocuments()->detach();
        }

        if (!empty($documentsToDelete)) {
            $documents = $landShipping->documents()->whereIn('id', $documentsToDelete)->get();
            foreach ($documents as $document) {
                if ($document->path && Storage::disk('public')->exists($document->path)) {
                    Storage::disk('public')->delete($document->path);
                }
                $document->delete();
            }
        }

        if ($request->hasFile('documents_zip')) {
            $files = $request->file('documents_zip');
            $files = is_array($files) ? $files : [$files];

            foreach ($files as $file) {
                $path = $file->store("land_shipping_documents/{$landShipping->id}", 'public');

                LandShippingDocument::create([
                    'land_shipping_id' => $landShipping->id,
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        return redirect()
            ->route('road-shipments.index')
            ->with('status', 'تم تحديث الشحنة الدولية البرية بنجاح.');
    }

    public function destroy(LandShipping $landShipping)
    {
        $landShipping->delete();

        return redirect()
            ->route('road-shipments.index')
            ->with('status', 'تم حذف الشحنة الدولية البرية بنجاح.');
    }

    public function export(Request $request)
    {
        $query = LandShipping::query()->with([
            'company',
            'department',
            'documents',
            'currentStage',
            'locomotives',
            'customsPort',
            'customsData',
            'warehouseTracking.warehouse',
        ]);

        if ($request->filled('search')) {
            $search = trim((string) $request->get('search'));
            $query->where(function ($builder) use ($search) {
                $builder->where('operation_number', 'like', "%{$search}%")
                    ->orWhere('shipment_name', 'like', "%{$search}%")
                    ->orWhere('declaration_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('arrival_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('arrival_date', '<=', $request->date_to);
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $reports = $query->orderBy('operation_number')->get();

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="road-shipments.xls"',
        ];

        $callback = function () use ($reports) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                '#',
                'رقم العملية',
                'أرقام القواطر',
                'اسم الشحنة',
                'رقم البيان',
                'حالة البيان',
                'الشركة',
                'القسم',
                'المنفذ',
                'تاريخ الوصول',
                'تاريخ الخروج',
                'ايام المماسي',
                'تاريخ وصول المخزن',
                'المرحلة الحالية',
                'المستندات',
            ], "\t");

            foreach ($reports as $index => $report) {
                $customsStateLabel = match ($report->customsData?->state) {
                    1 => 'ضمان',
                    2 => 'سداد',
                    default => '-',
                };

                $dockingDays = '-';
                if ($report->arrival_date && $report->exit_date) {
                    $diffDays = $report->arrival_date->diffInDays($report->exit_date, false);
                    $dockingDays = $diffDays >= 2 ? $diffDays - 2 : 0;
                }

                $warehouseArrivalDate = $report->warehouseTracking?->event_date
                    ?? $report->warehouseTracking?->created_at
                    ?? $report->warehouse_arrival_date;

                $locomotiveNumbers = $report->locomotives
                    ->pluck('locomotive_number')
                    ->filter()
                    ->implode(', ');

                $documentNames = $report->documents
                    ->pluck('original_name')
                    ->filter()
                    ->implode(' | ');

                fputcsv($handle, [
                    $index + 1,
                    $report->operation_number,
                    $locomotiveNumbers ?: '-',
                    $report->shipment_name ?? '-',
                    $report->declaration_number ?? '-',
                    $customsStateLabel,
                    $report->company?->name ?? '-',
                    $report->department?->name ?? '-',
                    $report->customsPort?->name ?? '-',
                    $report->arrival_date?->format('Y-m-d') ?? '-',
                    $report->exit_date?->format('Y-m-d') ?? '-',
                    $dockingDays,
                    $warehouseArrivalDate?->format('Y-m-d') ?? '-',
                    $report->currentStage?->name ?? '-',
                    $documentNames ?: '-',
                ], "\t");
            }

            fclose($handle);
        };

        return response()->streamDownload($callback, 'road-shipments.xls', $headers);
    }

    public function downloadDocument(LandShippingDocument $document)
    {
        if (!$document->path || !Storage::disk('public')->exists($document->path)) {
            abort(404);
        }

        $filename = $document->original_name ?: basename($document->path);

        return Storage::disk('public')->download($document->path, $filename);
    }
}
