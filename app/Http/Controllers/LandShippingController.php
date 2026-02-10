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
    }

    public function index(Request $request)
    {
        $query = LandShipping::query()->with(['company', 'department', 'documents', 'currentStage', 'locomotives', 'customsPort']);

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

        $reports = $query->orderByDesc('id')->paginate(15)->withQueryString();

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
            'docking_days' => ['nullable', 'integer', 'min:0'],
            'documents_sent_date' => ['nullable', 'date'],

            'warehouse_arrival_date' => ['nullable', 'date'],
            'company_id' => ['required', 'exists:companies,id'],
            'department_id' => ['nullable', 'exists:departements,id'],
            'customs_port_id' => ['nullable', 'exists:customs_port,id'],
            'documents_zip' => ['nullable', 'file', 'mimes:zip', 'max:51200'],
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
            $file = $request->file('documents_zip');
            $path = $file->store("land_shipping_documents/{$landShipping->id}", 'public');

            LandShippingDocument::create([
                'land_shipping_id' => $landShipping->id,
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);
        }

        return redirect()
            ->route('road-shipments.index')
            ->with('status', 'تم إضافة الشحنة الدولية البرية بنجاح.');
    }

    public function edit(LandShipping $landShipping)
    {
        $landShipping->load(['locomotives', 'attachedDocuments']);

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
            'docking_days' => ['nullable', 'integer', 'min:0'],
            'documents_sent_date' => ['nullable', 'date'],

            'warehouse_arrival_date' => ['nullable', 'date'],
            'company_id' => ['required', 'exists:companies,id'],
            'department_id' => ['nullable', 'exists:departements,id'],
            'customs_port_id' => ['nullable', 'exists:customs_port,id'],
            'documents_zip' => ['nullable', 'file', 'mimes:zip', 'max:51200'],
            'attached_documents' => ['nullable', 'array'],
            'attached_documents.*' => ['integer'],
        ]);

        $attachedDocuments = $data['attached_documents'] ?? [];
        unset($data['documents_zip'], $data['attached_documents'], $data['locomotive_numbers']);

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

        if ($request->hasFile('documents_zip')) {
            $file = $request->file('documents_zip');
            $path = $file->store("land_shipping_documents/{$landShipping->id}", 'public');

            LandShippingDocument::create([
                'land_shipping_id' => $landShipping->id,
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);
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

    public function downloadDocument(LandShippingDocument $document)
    {
        if (!$document->path || !Storage::disk('public')->exists($document->path)) {
            abort(404);
        }

        $filename = $document->original_name ?: basename($document->path);

        return Storage::disk('public')->download($document->path, $filename);
    }
}
