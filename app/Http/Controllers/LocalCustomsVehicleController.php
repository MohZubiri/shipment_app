<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\LocalCustomsVehicle;
use App\Models\Departement;
use App\Models\Warehouse;
use App\Models\CustomsPort;
use App\Models\LocalShipmentCustoms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocalCustomsVehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = LocalCustomsVehicle::query()->with(['company', 'department', 'warehouse']);

        if ($request->filled('search')) {
            $search = trim((string) $request->get('search'));
            $query->where(function ($builder) use ($search) {
                $builder->where('vehicle_plate_number', 'like', "%{$search}%")
                    ->orWhere('driver_name', 'like', "%{$search}%");

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

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $vehicles = $query->orderByDesc('id')->paginate(15)->withQueryString();

        $companies = Company::query()->orderBy('name')->get();
        $departments = Departement::query()->orderBy('name')->get();

        return view('local_customs_vehicles.index', compact('vehicles', 'companies', 'departments'));
    }

    public function create()
    {
        return view('local_customs_vehicles.create', [
            'companies' => Company::query()->orderBy('name')->get(),
            'departments' => Departement::query()->orderBy('name')->get(),
            'warehouses' => Warehouse::query()->orderBy('name')->get(),
            'customsPorts' => CustomsPort::query()->orderBy('name')->get(),
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
            'company_id' => ['required', 'exists:companies,id'],
            'department_id' => ['nullable', 'exists:departements,id'],
            'driver_name' => ['nullable', 'string', 'max:200'],
            'driver_phone' => ['nullable', 'string', 'max:50'],
            'factory_departure_date' => ['nullable', 'date'],
            'warehouse_arrival_date' => ['nullable', 'date'],
            'warehouse_id' => ['nullable', 'exists:warehouses,id'],
            'checkpoints' => ['nullable', 'array'],
            'checkpoints.*.customs_port_id' => ['required', 'exists:customs_port,id'],
            'checkpoints.*.entry_date' => ['nullable', 'date'],
            'checkpoints.*.entry_time' => ['nullable', 'date_format:H:i'],
            'checkpoints.*.exit_date' => ['nullable', 'date'],
            'checkpoints.*.exit_time' => ['nullable', 'date_format:H:i'],
        ]);

        $checkpoints = $data['checkpoints'] ?? [];
        unset($data['checkpoints']);

        $data['created_by'] = Auth::user()?->name;
        $data['is_active'] = $request->boolean('is_active');

        $localCustomsVehicle = LocalCustomsVehicle::create($data);

        foreach ($checkpoints as $checkpoint) {
            if (!empty($checkpoint['customs_port_id'])) {
                $localCustomsVehicle->customs()->create($checkpoint);
            }
        }

        return redirect()
            ->route('local-shipments.index')
            ->with('status', 'تم إضافة الشحنة المحلية البرية بنجاح.');
    }

    public function edit(LocalCustomsVehicle $localCustomsVehicle)
    {
        return view('local_customs_vehicles.edit', [
            'localCustomsVehicle' => $localCustomsVehicle->load('customs'),
            'companies' => Company::query()->orderBy('name')->get(),
            'departments' => Departement::query()->orderBy('name')->get(),
            'warehouses' => Warehouse::query()->orderBy('name')->get(),
            'customsPorts' => CustomsPort::query()->orderBy('name')->get(),
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
            'company_id' => ['required', 'exists:companies,id'],
            'department_id' => ['nullable', 'exists:departements,id'],
            'driver_name' => ['nullable', 'string', 'max:200'],
            'driver_phone' => ['nullable', 'string', 'max:50'],
            'factory_departure_date' => ['nullable', 'date'],
            'warehouse_arrival_date' => ['nullable', 'date'],
            'warehouse_id' => ['nullable', 'exists:warehouses,id'],
            'checkpoints' => ['nullable', 'array'],
            'checkpoints.*.customs_port_id' => ['required', 'exists:customs_port,id'],
            'checkpoints.*.entry_date' => ['nullable', 'date'],
            'checkpoints.*.entry_time' => ['nullable', 'date_format:H:i'],
            'checkpoints.*.exit_date' => ['nullable', 'date'],
            'checkpoints.*.exit_time' => ['nullable', 'date_format:H:i'],
        ]);

        $checkpoints = $data['checkpoints'] ?? [];
        unset($data['checkpoints']);

        $data['is_active'] = $request->boolean('is_active');

        $localCustomsVehicle->update($data);

        $localCustomsVehicle->customs()->delete();
        foreach ($checkpoints as $checkpoint) {
            if (!empty($checkpoint['customs_port_id'])) {
                $localCustomsVehicle->customs()->create($checkpoint);
            }
        }



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
