<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShipmentRequest;
use App\Models\Departement;
use App\Models\Section;
use App\Models\Shipment;
use App\Models\ShipmentDocument;
use App\Models\Shipgroup;
use App\Models\ShippingLine;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Shipment::query()
            ->with(['department', 'section', 'shipgroup', 'shippingLine', 'documents']);

        $this->applyFilters($query, $request);

        $shipments = $query
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('shipments.index', [
            'shipments' => $shipments,
            'departments' => Departement::query()->orderBy('name')->get(),
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
        ]);
    }

    public function store(StoreShipmentRequest $request)
    {
        $data = $request->validated();
        unset($data['bill_of_lading']);

        $shipment = Shipment::create($data);

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

    public function export(Request $request)
    {
        $query = Shipment::query()
            ->with(['department', 'section', 'shippingLine']);

        $this->applyFilters($query, $request);

        $shipments = $query->orderByDesc('id')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="shipments.csv"',
        ];

        $callback = function () use ($shipments) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'ID',
                'Operation No',
                'Bill No',
                'Container No',
                'Department',
                'Section',
                'Shipping Line',
                'Arrival Date',
                'End Allow Date',
                'Still Days',
                'Created At',
            ]);

            foreach ($shipments as $shipment) {
                fputcsv($handle, [
                    $shipment->id,
                    $shipment->operationno,
                    $shipment->pillno,
                    $shipment->pilno,
                    $shipment->department?->name,
                    $shipment->section?->name,
                    $shipment->shippingLine?->name,
                    $shipment->dategase?->format('Y-m-d'),
                    $shipment->endallowdate?->format('Y-m-d'),
                    $shipment->stillday,
                    $shipment->created_at?->format('Y-m-d H:i'),
                ]);
            }

            fclose($handle);
        };

        return response()->streamDownload($callback, 'shipments.csv', $headers);
    }

    private function applyFilters(Builder $query, Request $request): void
    {
        $search = trim((string) $request->get('search'));
        if ($search !== '') {
            $query->where(function (Builder $builder) use ($search) {
                $builder->where('pillno', 'like', "%{$search}%")
                    ->orWhere('pilno', 'like', "%{$search}%")
                    ->orWhere('orginalno', 'like', "%{$search}%");

                if (is_numeric($search)) {
                    $builder->orWhere('operationno', (int) $search);
                    $builder->orWhere('datano', (int) $search);
                }
            });
        }

        $query->when($request->filled('departmentno'), fn (Builder $builder) => $builder->where('departmentno', $request->departmentno));
        $query->when($request->filled('sectionno'), fn (Builder $builder) => $builder->where('sectionno', $request->sectionno));
        $query->when($request->filled('shippingno'), fn (Builder $builder) => $builder->where('shippingno', $request->shippingno));

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
