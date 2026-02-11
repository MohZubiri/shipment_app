<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShipmentRequest;
use App\Models\CustomsPort;
use App\Models\Company;
use App\Models\Departement;
use App\Models\ShipmentTransaction;
use App\Models\ShipmentDocument;
use App\Models\Shipgroup;
use App\Models\ShippingLine;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShipmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view shipments')->only(['index', 'show', 'downloadDocument']);
        $this->middleware('permission:create shipments')->only(['create', 'store']);
        $this->middleware('permission:edit shipments')->only(['edit', 'update']);
        $this->middleware('permission:delete shipments')->only(['destroy']);
        $this->middleware('permission:export shipments')->only(['export']);
    }

    public function index(Request $request)
    {
        $query = ShipmentTransaction::query()
            ->with([
                'company',
                'department',
                'shipgroup',
                'shippingLine',
                'customsData',
                'customsPort',
                'shipment',
                'shipmentType',
                'shipmentStatus',
                'currentStage',
                'containers',
                'documents',
            ]);

        $this->applyFilters($query, $request);

        // Handle sorting
        $sortColumn = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        // Validate sort column to prevent SQL injection
        $allowedColumns = ['id', 'operationno', 'shippmintno', 'pillno', 'datano', 'dategase', 'endallowdate', 'stillday', 'created_at'];
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'created_at';
        }

        $sortDirection = in_array($sortDirection, ['asc', 'desc']) ? $sortDirection : 'desc';

        $shipments = $query
            ->orderBy($sortColumn, $sortDirection)
            ->paginate(15)
            ->withQueryString();

        return view('shipments.index', [
            'shipments' => $shipments,
            'companies' => Company::query()->orderBy('name')->get(),
            'ports' => CustomsPort::query()->orderBy('name')->get(),
            'departments' => Departement::query()->orderBy('name')->get(),
            'shippingLines' => ShippingLine::query()->orderBy('name')->get(),
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function create()
    {
        return view('shipments.create', [
            'companies' => Company::query()->orderBy('name')->get(),
            'departments' => Departement::query()->orderBy('name')->get(),
            'shipgroups' => Shipgroup::query()->orderBy('name')->get(),
            'shippingLines' => ShippingLine::query()->orderBy('name')->get(),
            'customsPorts' => \App\Models\CustomsPort::query()->orderBy('name')->get(),
            'shipmentTypes' => \App\Models\ShipmentType::query()->orderBy('name')->get(),
            'shipmentStatuses' => \App\Models\ShipmentStatus::query()->orderBy('name')->get(),
            'customsDataList' => \App\Models\CustomsData::query()->orderByDesc('datano')->pluck('datano'),
            'shipmentsList' => \App\Models\Shipment::query()->orderBy('name')->get(['id', 'name', 'quantity', 'status']),
            'activeDocuments' => \App\Models\Document::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function show(ShipmentTransaction $shipment)
    {
        $shipment->load([
            'company',
            'department',
            'shipgroup',
            'shippingLine',
            'customsPort',
            'customsData',
            'shipment',
            'shipmentType',
            'shipmentStatus',
            'containers',
            'documents',
            'currentStage',
            'trackingRecords',
        ]);

        return view('shipments.show', [
            'shipment' => $shipment,
        ]);
    }

    public function edit(ShipmentTransaction $shipment)
    {
        $shipment->load(['containers', 'documents', 'attachedDocuments']);

        return view('shipments.create', [
            'shipment' => $shipment,
            'companies' => Company::query()->orderBy('name')->get(),
            'departments' => Departement::query()->orderBy('name')->get(),
            'shipgroups' => Shipgroup::query()->orderBy('name')->get(),
            'shippingLines' => ShippingLine::query()->orderBy('name')->get(),
            'customsPorts' => \App\Models\CustomsPort::query()->orderBy('name')->get(),
            'shipmentTypes' => \App\Models\ShipmentType::query()->orderBy('name')->get(),
            'shipmentStatuses' => \App\Models\ShipmentStatus::query()->orderBy('name')->get(),
            'customsDataList' => \App\Models\CustomsData::query()->orderByDesc('datano')->pluck('datano'),
            'shipmentsList' => \App\Models\Shipment::query()->orderBy('name')->get(['id', 'name', 'quantity', 'status']),
            'activeDocuments' => \App\Models\Document::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(StoreShipmentRequest $request)
    {
        $data = $request->validated();
        if (!array_key_exists('pilno', $data)) {
            $data['pilno'] = '';
        }
        $attachedDocuments = $data['attached_documents'] ?? [];
        unset($data['bill_of_lading']);
        unset($data['containers']);
        unset($data['attached_documents']);
        unset($data['documents_zip']);
        unset($data['documents_to_delete']);

        $shipment = ShipmentTransaction::create($data);

        // Save attached documents
        if (!empty($attachedDocuments)) {
            $shipment->attachedDocuments()->sync($attachedDocuments);
        }

        // Save containers
        if ($request->has('containers')) {
            foreach ($request->input('containers') as $containerData) {
                $billOfLading = $containerData['bill_of_lading'] ?? null;
                if ($billOfLading === null || $billOfLading === '') {
                    $billOfLading = $data['pillno'] ?? null;
                }

                $shipment->containers()->create([
                    'invoice_number' => $containerData['invoice_number'] ?? null,
                    'packing_list_number' => $containerData['packing_list_number'] ?? null,
                    'certificate_of_origin' => $containerData['certificate_of_origin'] ?? null,
                    'bill_of_lading' => $billOfLading,
                    'container_count' => $containerData['container_count'] ?? 1,
                    'container_size' => $containerData['container_size'] ?? null,
                ]);
            }
        }

        // Handle document uploads (multiple files allowed)
        if ($request->hasFile('documents_zip')) {
            $files = $request->file('documents_zip');
            if (!is_array($files)) {
                $files = [$files];
            }

            foreach ($files as $file) {
                if (!$file) {
                    continue;
                }

                $path = $file->store("shipment_documents/{$shipment->id}", 'public');

                ShipmentDocument::create([
                    'shipment_id' => $shipment->id,
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        // Legacy support for bill_of_lading files
        if ($request->hasFile('bill_of_lading')) {
            foreach ($request->file('bill_of_lading') as $file) {
                $path = $file->store("bill_of_lading/{$shipment->id}", 'public');

                ShipmentDocument::create([
                    'shipment_id' => $shipment->id,
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        return redirect()
            ->route('shipments.create')
            ->with('status', 'تم حفظ الشحنة بنجاح وتم احتساب فترة السماح تلقائياً.');
    }

    public function update(StoreShipmentRequest $request, ShipmentTransaction $shipment)
    {
        $data = $request->validated();
        $attachedDocuments = $data['attached_documents'] ?? [];
        unset($data['bill_of_lading']);
        unset($data['containers']);
        unset($data['attached_documents']);
        unset($data['documents_zip']);
        unset($data['documents_to_delete']);

        $shipment->update($data);

        // Sync attached documents
        $shipment->attachedDocuments()->sync($attachedDocuments);

        $shipment->containers()->delete();
        if ($request->has('containers')) {
            foreach ($request->input('containers') as $containerData) {
                $billOfLading = $containerData['bill_of_lading'] ?? null;
                if ($billOfLading === null || $billOfLading === '') {
                    $billOfLading = $data['pillno'] ?? null;
                }

                $shipment->containers()->create([
                    'invoice_number' => $containerData['invoice_number'] ?? null,
                    'packing_list_number' => $containerData['packing_list_number'] ?? null,
                    'certificate_of_origin' => $containerData['certificate_of_origin'] ?? null,
                    'bill_of_lading' => $billOfLading,
                    'container_count' => $containerData['container_count'] ?? 1,
                    'container_size' => $containerData['container_size'] ?? null,
                ]);
            }
        }

        $documentsToDelete = $request->input('documents_to_delete', []);
        if (!empty($documentsToDelete)) {
            $documents = $shipment->documents()
                ->whereIn('id', $documentsToDelete)
                ->get();

            foreach ($documents as $document) {
                if ($document->path) {
                    Storage::disk('public')->delete($document->path);
                }
                $document->delete();
            }
        }

        if ($request->hasFile('documents_zip')) {
            $files = $request->file('documents_zip');
            if (!is_array($files)) {
                $files = [$files];
            }

            foreach ($files as $file) {
                if (!$file) {
                    continue;
                }

                $path = $file->store("shipment_documents/{$shipment->id}", 'public');

                ShipmentDocument::create([
                    'shipment_id' => $shipment->id,
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        return redirect()
            ->route('shipments.index')
            ->with('status', 'تم تحديث الشحنة بنجاح.');
    }

    public function destroy(ShipmentTransaction $shipment)
    {
        $shipment->delete();

        return redirect()
            ->route('shipments.index')
            ->with('status', 'تم حذف الشحنة بنجاح.');
    }

    public function export(Request $request)
    {
        $query = ShipmentTransaction::query()
            ->with(['company', 'department', 'shippingLine', 'customsData', 'customsPort', 'currentStage', 'containers']);

        $this->applyFilters($query, $request);

        $shipments = $query->orderByDesc('id')->get();

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="shipments.xls"',
        ];

        $callback = function () use ($shipments) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                '#',
                'العملية',
                'اسم الشحنة',
                'البوليصة',
                'عدد الحاويات',
                'البيان الجمركي',
                'حالة البيان',
                'المنفذ',
                'الشركة',
                'القسم',
                'الخط الملاحي',
                'الوصول',
                'نهاية السماح',
                'تاريخ الإنشاء',
                'مرحلة الشحنة',
                'الأيام المتبقية',
            ], "\t");

            foreach ($shipments as $index => $shipment) {
                // Calculate container summary
                $containerCounts = $shipment->containers
                    ->filter(fn($container) => $container->container_size && $container->container_count)
                    ->groupBy('container_size')
                    ->map(fn($group) => $group->sum('container_count'));

                if ($containerCounts->isEmpty()) {
                    $containerCounts = collect();
                    if (($shipment->park40 ?? 0) > 0) {
                        $containerCounts->put('40', $shipment->park40);
                    }
                    if (($shipment->park20 ?? 0) > 0) {
                        $containerCounts->put('20', $shipment->park20);
                    }
                }

                $preferredOrder = ['40', '40HC', '20'];
                $orderedCounts = collect();
                foreach ($preferredOrder as $size) {
                    if ($containerCounts->has($size)) {
                        $orderedCounts->put($size, $containerCounts->get($size));
                    }
                }
                $orderedCounts = $orderedCounts->union($containerCounts->diffKeys($orderedCounts));
                $containerSummary = $orderedCounts->map(fn($count, $size) => "حاويات {$size} قدم عدد {$count}")->implode(', ');

                // Calculate customs state label
                $customsStateLabel = match ($shipment->customsData?->state) {
                    1 => 'ضمان',
                    2 => 'سداد',
                    default => '-',
                };

                // Calculate stage label
                $stageLabel = $shipment->currentStage?->name ?? 'غير محددة';

                // Calculate end allow date and still days
                $today = \Carbon\Carbon::today();
                $computedEndAllowDate = $shipment->endallowdate;
                $computedStillday = 0;
                $allowanceDays = $shipment->shippingLine?->time;
                if ($shipment->dategase && $allowanceDays) {
                    $computedEndAllowDate = \Carbon\Carbon::parse($shipment->dategase)->addDays((int) $allowanceDays);
                    $computedStillday = $today->diffInDays($computedEndAllowDate, false);
                } elseif ($computedEndAllowDate) {
                    $computedStillday = $today->diffInDays($computedEndAllowDate, false);
                }

                fputcsv($handle, [
                    $index + 1,
                    $shipment->operationno,
                    $shipment->shippmintno,
                    $shipment->pillno,
                    $containerSummary,
                    $shipment->datano,
                    $customsStateLabel,
                    $shipment->customsPort?->name,
                    $shipment->company?->name,
                    $shipment->department?->name,
                    $shipment->shippingLine?->name,
                    $shipment->dategase?->format('Y-m-d'),
                    $computedEndAllowDate?->format('Y-m-d'),
                    $shipment->created_at?->format('Y-m-d H:i'),
                    $stageLabel,
                    $computedStillday,
                ], "\t");
            }

            fclose($handle);
        };

        return response()->streamDownload($callback, 'shipments.xls', $headers);
    }

    public function downloadDocument(ShipmentDocument $document)
    {
        if (!$document->path || !Storage::disk('public')->exists($document->path)) {
            abort(404);
        }

        $filename = $document->original_name ?: basename($document->path);

        return Storage::disk('public')->download($document->path, $filename);
    }

    private function applyFilters(Builder $query, Request $request): void
    {
        $search = trim((string) $request->get('search'));
        if ($search !== '') {
            $query->where(function (Builder $builder) use ($search) {
                $builder->where('pillno', 'like', "%{$search}%")
                    ->orWhere('pilno', 'like', "%{$search}%")
                    ->orWhere('orginalno', 'like', "%{$search}%")
                    ->orWhere('shippmintno', 'like', "%{$search}%");

                if (is_numeric($search)) {
                    $builder->orWhere('operationno', (int) $search);
                    $builder->orWhere('datano', (int) $search);
                }
            });
        }

        $query->when($request->filled('company_id'), fn (Builder $builder) => $builder->where('company_id', $request->company_id));
        $query->when($request->filled('customs_port_id'), fn (Builder $builder) => $builder->where('customs_port_id', $request->customs_port_id));
        $query->when($request->filled('department_id'), fn (Builder $builder) => $builder->where('department_id', $request->department_id));
        $query->when($request->filled('shippingno'), fn (Builder $builder) => $builder->where('shippingno', $request->shippingno));
        $customsState = $request->get('customs_state', $request->get('dectype'));
        if ($customsState !== null && $customsState !== '') {
            if ($customsState === 'ضمان') {
                $customsState = 1;
            } elseif ($customsState === 'سداد') {
                $customsState = 2;
            }
            $query->whereHas('customsData', fn (Builder $builder) => $builder->where('state', $customsState));
        }

        if ($request->filled('dategase_from')) {
            $query->whereDate('dategase', '>=', $request->dategase_from);
        }

        if ($request->filled('dategase_to')) {
            $query->whereDate('dategase', '<=', $request->dategase_to);
        }

        $status = $request->get('stillday_status');
        if ($status === 'near') {
            $query->whereBetween('stillday', [0, 3]);
        } elseif ($status === 'expired') {
            $query->where('stillday', '<', 0);
        } elseif ($status === 'available') {
            $query->where('stillday', '>=', 4);
        }
    }
}
