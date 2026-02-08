<?php

namespace App\Http\Controllers;

use App\Models\ShippingReport;
use Illuminate\Http\Request;

class ShippingReportController extends Controller
{
    public function index(Request $request)
    {
        $query = ShippingReport::query();

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

        $reports = $query->orderByDesc('id')->paginate(15)->withQueryString();

        return view('shipping_reports.index', compact('reports'));
    }

    public function create()
    {
        return view('shipping_reports.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'operation_number' => ['required', 'string', 'max:50'],
            'locomotive_number' => ['nullable', 'string', 'max:50'],
            'shipment_name' => ['nullable', 'string', 'max:200'],
            'declaration_number' => ['nullable', 'string', 'max:50'],
            'arrival_date' => ['nullable', 'date'],
            'exit_date' => ['nullable', 'date'],
            'docking_days' => ['nullable', 'integer', 'min:0'],
            'documents_sent_date' => ['nullable', 'date'],
            'documents_type' => ['nullable', 'string', 'max:100'],
            'warehouse_arrival_date' => ['nullable', 'date'],
        ]);

        ShippingReport::create($data);

        return redirect()
            ->route('road-shipments.index')
            ->with('status', 'تم إضافة الشحنة الدولية البرية بنجاح.');
    }

    public function edit(ShippingReport $shippingReport)
    {
        return view('shipping_reports.edit', compact('shippingReport'));
    }

    public function update(Request $request, ShippingReport $shippingReport)
    {
        $data = $request->validate([
            'operation_number' => ['required', 'string', 'max:50'],
            'locomotive_number' => ['nullable', 'string', 'max:50'],
            'shipment_name' => ['nullable', 'string', 'max:200'],
            'declaration_number' => ['nullable', 'string', 'max:50'],
            'arrival_date' => ['nullable', 'date'],
            'exit_date' => ['nullable', 'date'],
            'docking_days' => ['nullable', 'integer', 'min:0'],
            'documents_sent_date' => ['nullable', 'date'],
            'documents_type' => ['nullable', 'string', 'max:100'],
            'warehouse_arrival_date' => ['nullable', 'date'],
        ]);

        $shippingReport->update($data);

        return redirect()
            ->route('road-shipments.index')
            ->with('status', 'تم تحديث الشحنة الدولية البرية بنجاح.');
    }

    public function destroy(ShippingReport $shippingReport)
    {
        $shippingReport->delete();

        return redirect()
            ->route('road-shipments.index')
            ->with('status', 'تم حذف الشحنة الدولية البرية بنجاح.');
    }
}
