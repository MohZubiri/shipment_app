<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">تعديل شحنة محلية برية</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('local-shipments.update', $localCustomsVehicle) }}"
                class="bg-white shadow-sm sm:rounded-xl p-6 space-y-6 border border-slate-100">
                @csrf
                @method('PUT')

                @if($errors->any())
                    <div class="bg-amber-50 text-amber-800 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="serial_number">رقم التسلسل</label>
                        <input id="serial_number" name="serial_number" type="number"
                            value="{{ old('serial_number', $localCustomsVehicle->serial_number) }}" required
                            class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="vehicle_plate_number">رقم اللوحة</label>
                        <input id="vehicle_plate_number" name="vehicle_plate_number" type="text"
                            value="{{ old('vehicle_plate_number', $localCustomsVehicle->vehicle_plate_number) }}"
                            class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="company_id">الشركة</label>
                        <select id="company_id" name="company_id" required class="mt-2 w-full rounded-md border-slate-300">
                            <option value="">اختر الشركة</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" @selected(old('company_id', $localCustomsVehicle->company_id) == $company->id)>{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="section_id">القسم</label>
                        <select id="section_id" name="section_id" class="mt-2 w-full rounded-md border-slate-300">
                            <option value="">اختياري</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" @selected(old('section_id', $localCustomsVehicle->section_id) == $department->id)>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="user_name">اسم المستخدم</label>
                        <input id="user_name" name="user_name" type="text"
                            value="{{ old('user_name', $localCustomsVehicle->user_name) }}"
                            class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="arrival_time_from_branch">وقت الوصول من الفرع</label>
                        <input id="arrival_time_from_branch" name="arrival_time_from_branch" type="time"
                            value="{{ old('arrival_time_from_branch', $localCustomsVehicle->arrival_time_from_branch) }}"
                            class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="departure_time_to_branch">وقت المغادرة إلى الفرع</label>
                        <input id="departure_time_to_branch" name="departure_time_to_branch" type="time"
                            value="{{ old('departure_time_to_branch', $localCustomsVehicle->departure_time_to_branch) }}"
                            class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="arrival_date_from_branch">تاريخ الوصول من الفرع</label>
                        <input id="arrival_date_from_branch" name="arrival_date_from_branch" type="date"
                            value="{{ old('arrival_date_from_branch', $localCustomsVehicle->arrival_date_from_branch?->format('Y-m-d')) }}"
                            class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="destination">الوجهة</label>
                        <input id="destination" name="destination" type="text"
                            value="{{ old('destination', $localCustomsVehicle->destination) }}"
                            class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="cargo_type">نوع البضاعة</label>
                        <input id="cargo_type" name="cargo_type" type="text"
                            value="{{ old('cargo_type', $localCustomsVehicle->cargo_type) }}"
                            class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                    <div class="lg:col-span-3">
                        <label class="block text-sm font-medium text-slate-700" for="cargo_description">وصف البضاعة</label>
                        <input id="cargo_description" name="cargo_description" type="text"
                            value="{{ old('cargo_description', $localCustomsVehicle->cargo_description) }}"
                            class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="vehicle_number">رقم السيارة</label>
                        <input id="vehicle_number" name="vehicle_number" type="text"
                            value="{{ old('vehicle_number', $localCustomsVehicle->vehicle_number) }}"
                            class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="manufacture_date">تاريخ التصنيع</label>
                        <input id="manufacture_date" name="manufacture_date" type="date"
                            value="{{ old('manufacture_date', $localCustomsVehicle->manufacture_date?->format('Y-m-d')) }}"
                            class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="exit_date_from_manufacture">تاريخ الخروج من التصنيع</label>
                        <input id="exit_date_from_manufacture" name="exit_date_from_manufacture" type="date"
                            value="{{ old('exit_date_from_manufacture', $localCustomsVehicle->exit_date_from_manufacture?->format('Y-m-d')) }}"
                            class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                    <div class="lg:col-span-3">
                        <label class="block text-sm font-medium text-slate-700" for="notes">ملاحظات</label>
                        <input id="notes" name="notes" type="text"
                            value="{{ old('notes', $localCustomsVehicle->notes) }}"
                            class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                    <div class="flex items-center gap-2">
                        <input id="is_active" name="is_active" type="checkbox" value="1" class="rounded border-slate-300 text-blue-600"
                            {{ old('is_active', $localCustomsVehicle->is_active) ? 'checked' : '' }}>
                        <label class="text-sm text-slate-700" for="is_active">نشط</label>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('local-shipments.index') }}" class="px-4 py-2 border rounded-md text-slate-700">إلغاء</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">تحديث</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
