<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use App\Models\LocalCustomsVehicle;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocalCustomsVehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = LocalCustomsVehicle::query()->with(['company', 'department']);

        if ($request->filled('search')) {
            $search = trim((string) $request->get('search'));
            $query->where(function ($builder) use ($search) {
                $builder->where('vehicle_plate_number', 'like', "%{$search}%")
                    ->orWhere('user_name', 'like', "%{$search}%");

                if (is_numeric($search)) {
                    $builder->orWhere('serial_number', (int) $search);
                }
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('arrival_date_from_branch', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('arrival_date_from_branch', '<=', $request->date_to);
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->filled('section_id')) {
            $query->where('section_id', $request->section_id);
        }

        $vehicles = $query->orderByDesc('id')->paginate(15)->withQueryString();

        $companies = Departement::query()->orderBy('name')->get();
        $departments = Section::query()->orderBy('name')->get();

        return view('local_customs_vehicles.index', compact('vehicles', 'companies', 'departments'));
    }

    public function create()
    {
        return view('local_customs_vehicles.create', [
            'companies' => Departement::query()->orderBy('name')->get(),
            'departments' => Section::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'serial_number' => ['required', 'integer', 'min:1'],
            'vehicle_plate_number' => ['nullable', 'string', 'max:50'],
            'user_name' => ['nullable', 'string', 'max:200'],
            'arrival_time_from_branch' => ['nullable', 'date_format:H:i'],
            'departure_time_to_branch' => ['nullable', 'date_format:H:i'],
            'arrival_date_from_branch' => ['nullable', 'date'],
            'destination' => ['nullable', 'string', 'max:200'],
            'cargo_type' => ['nullable', 'string', 'max:100'],
            'cargo_description' => ['nullable', 'string', 'max:500'],
            'vehicle_number' => ['nullable', 'string', 'max:50'],
            'manufacture_date' => ['nullable', 'date'],
            'exit_date_from_manufacture' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
            'company_id' => ['required', 'exists:departement,id'],
            'section_id' => ['nullable', 'exists:section,id'],
        ]);

        $data['created_by'] = Auth::user()?->name;
        $data['is_active'] = $request->boolean('is_active');

        LocalCustomsVehicle::create($data);

        return redirect()
            ->route('local-shipments.index')
            ->with('status', 'تم إضافة الشحنة المحلية البرية بنجاح.');
    }

    public function edit(LocalCustomsVehicle $localCustomsVehicle)
    {
        return view('local_customs_vehicles.edit', [
            'localCustomsVehicle' => $localCustomsVehicle,
            'companies' => Departement::query()->orderBy('name')->get(),
            'departments' => Section::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, LocalCustomsVehicle $localCustomsVehicle)
    {
        $data = $request->validate([
            'serial_number' => ['required', 'integer', 'min:1'],
            'vehicle_plate_number' => ['nullable', 'string', 'max:50'],
            'user_name' => ['nullable', 'string', 'max:200'],
            'arrival_time_from_branch' => ['nullable', 'date_format:H:i'],
            'departure_time_to_branch' => ['nullable', 'date_format:H:i'],
            'arrival_date_from_branch' => ['nullable', 'date'],
            'destination' => ['nullable', 'string', 'max:200'],
            'cargo_type' => ['nullable', 'string', 'max:100'],
            'cargo_description' => ['nullable', 'string', 'max:500'],
            'vehicle_number' => ['nullable', 'string', 'max:50'],
            'manufacture_date' => ['nullable', 'date'],
            'exit_date_from_manufacture' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
            'company_id' => ['required', 'exists:departement,id'],
            'section_id' => ['nullable', 'exists:section,id'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $localCustomsVehicle->update($data);

        return redirect()
            ->route('local-shipments.index')
            ->with('status', 'تم تحديث الشحنة المحلية البرية بنجاح.');
    }

    public function destroy(LocalCustomsVehicle $localCustomsVehicle)
    {
        $localCustomsVehicle->delete();

        return redirect()
            ->route('local-shipments.index')
            ->with('status', 'تم حذف الشحنة المحلية البرية بنجاح.');
    }
}
