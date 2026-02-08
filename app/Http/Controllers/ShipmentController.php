<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShipmentRequest;
use App\Models\CustomsPort;
use App\Models\Departement;
use App\Models\Section;
use App\Models\ShipmentTransaction;
use App\Models\ShipmentDocument;
use App\Models\Shipgroup;
use App\Models\ShippingLine;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = ShipmentTransaction::query()
            ->with([
                'department',
                'section',
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

        $shipments = $query
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('shipments.index', [
            'shipments' => $shipments,
            'departments' => Departement::query()->orderBy('name')->get(),
            'ports' => CustomsPort::query()->orderBy('name')->get(),
            'sections' => Section::query()->orderBy('name')->get(),
            'shippingLines' => ShippingLine::query()->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('shipments.create', [
            'departments' => Departement::query()->orderBy('name')->get(),
            'sections' => Section::query()->orderBy('name')->get(),
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
            'department',
            'section',
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
        $shipment->load(['containers', 'documents']);

        return view('shipments.create', [
            'shipment' => $shipment,
            'departments' => Departement::query()->orderBy('name')->get(),
            'sections' => Section::query()->orderBy('name')->get(),
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
        unset($data['bill_of_lading']);
        unset($data['containers']);
        unset($data['attached_documents']);
        unset($data['documents_zip']);

        $shipment = ShipmentTransaction::create($data);

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

        // Handle ZIP file upload
        if ($request->hasFile('documents_zip')) {
            $file = $request->file('documents_zip');
            $path = $file->store("shipment_documents/{$shipment->id}", 'public');

            ShipmentDocument::create([
                'shipment_id' => $shipment->id,
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);
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
        unset($data['bill_of_lading']);
        unset($data['containers']);
        unset($data['attached_documents']);
        unset($data['documents_zip']);

        $shipment->update($data);

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
            ->with(['department', 'section', 'shippingLine']);

        $this->applyFilters($query, $request);

        $shipments = $query->orderByDesc('id')->get();

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="shipments.xls"',
        ];

        $callback = function () use ($shipments) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'ID',
                'Operation No',
                'Shipment Name',
                'Bill No',
                'Container No',
                'Department',
                'Section',
                'Shipping Line',
                'Arrival Date',
                'End Allow Date',
                'Still Days',
                'Created At',
            ], "\t");

            foreach ($shipments as $shipment) {
                fputcsv($handle, [
                    $shipment->id,
                    $shipment->operationno,
                    $shipment->shippmintno,
                    $shipment->pillno,
                    $shipment->pilno,
                    $shipment->department?->name,
                    $shipment->section?->name,
                    $shipment->shippingLine?->name,
                    $shipment->dategase?->format('Y-m-d'),
                    $shipment->endallowdate?->format('Y-m-d'),
                    $shipment->stillday,
                    $shipment->created_at?->format('Y-m-d H:i'),
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

        $query->when($request->filled('departmentno'), fn (Builder $builder) => $builder->where('departmentno', $request->departmentno));
        $query->when($request->filled('customs_port_id'), fn (Builder $builder) => $builder->where('customs_port_id', $request->customs_port_id));
        $query->when($request->filled('sectionno'), fn (Builder $builder) => $builder->where('sectionno', $request->sectionno));
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
