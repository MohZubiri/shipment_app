<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">إضافة شحنة محلية برية</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('local-shipments.store') }}"
                class="bg-white shadow-sm sm:rounded-xl p-6 space-y-8 border border-slate-100">
                @csrf

                @if($errors->any())
                    <div class="bg-amber-50 text-amber-800 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Section 1: Shipment Details -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-slate-900 border-b pb-2">تفاصيل الشحنة</h3>
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="serial_number">رقم
                                العملية</label>
                            <input id="serial_number" name="serial_number" type="number"
                                value="{{ old('serial_number') }}" required
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="company_id">الشركة</label>
                            <select id="company_id" name="company_id" required
                                class="mt-2 w-full rounded-md border-slate-300">
                                <option value="">اختر الشركة</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" @selected(old('company_id') == $company->id)>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="department_id">القسم</label>
                            <select id="department_id" name="department_id"
                                class="mt-2 w-full rounded-md border-slate-300">
                                <option value="">اختر القسم</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" @selected(old('department_id') == $department->id)>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="cargo_type">نوع البضاعة</label>
                            <input id="cargo_type" name="cargo_type" type="text" value="{{ old('cargo_type') }}"
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-slate-700" for="cargo_description">وصف
                                البضاعة</label>
                            <input id="cargo_description" name="cargo_description" type="text"
                                value="{{ old('cargo_description') }}" class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Driver & Vehicle -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-slate-900 border-b pb-2">السائق والمركبة</h3>
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="driver_name">اسم السائق</label>
                            <input id="driver_name" name="driver_name" type="text" value="{{ old('driver_name') }}"
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="driver_phone">رقم هاتف
                                السائق</label>
                            <input id="driver_phone" name="driver_phone" type="text" value="{{ old('driver_phone') }}"
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="vehicle_number">رقم
                                القيد</label>
                            <input id="vehicle_number" name="vehicle_number" type="text"
                                value="{{ old('vehicle_number') }}" class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="vehicle_plate_number">رقم
                                اللوحة</label>
                            <input id="vehicle_plate_number" name="vehicle_plate_number" type="text"
                                value="{{ old('vehicle_plate_number') }}"
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                    </div>
                </div>

                <!-- Section 3: Route Details (Factory & Warehouse) -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-slate-900 border-b pb-2">تفاصيل المسار</h3>
                    <div class="grid gap-4 sm:grid-cols-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="factory_departure_date">تاريخ
                                مغادرة المصنع</label>
                            <input id="factory_departure_date" name="factory_departure_date" type="date"
                                value="{{ old('factory_departure_date') }}"
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="warehouse_id">المخزن</label>
                            <select id="warehouse_id" name="warehouse_id"
                                class="mt-2 w-full rounded-md border-slate-300">
                                <option value="">اختر المخزن</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" @selected(old('warehouse_id') == $warehouse->id)>
                                        {{ $warehouse->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="warehouse_arrival_date">تاريخ
                                الوصول إلى المخزن</label>
                            <input id="warehouse_arrival_date" name="warehouse_arrival_date" type="date"
                                value="{{ old('warehouse_arrival_date') }}"
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                    </div>
                </div>

                <!-- Section 4: Customs Checkpoints -->
                <div class="space-y-4">
                    <div class="flex justify-between items-center border-b pb-2">
                        <h3 class="text-lg font-medium text-slate-900">نقاط الجمارك</h3>
                        <button type="button" onclick="addCheckpoint()"
                            class="text-sm text-blue-600 hover:text-blue-800 font-medium cursor-pointer">
                            + إضافة نقطة جمرك
                        </button>
                    </div>

                    <div id="checkpoints-container" class="space-y-4">
                        {{-- Checkpoints will be added here --}}
                        @if(old('checkpoints'))
                            @foreach(old('checkpoints') as $index => $checkpoint)
                                <div
                                    class="checkpoint-item grid gap-4 sm:grid-cols-5 p-4 bg-slate-50 rounded-lg border border-slate-200 relative">
                                    <button type="button" onclick="this.parentElement.remove()"
                                        class="absolute top-2 left-2 text-red-500 hover:text-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-700 mb-1">الجمرك</label>
                                        <select name="checkpoints[{{$index}}][customs_port_id]"
                                            class="w-full rounded-md border-slate-300 text-sm">
                                            <option value="">اختر الجمرك</option>
                                            @foreach($customsPorts as $port)
                                                <option value="{{ $port->id }}"
                                                    @selected($checkpoint['customs_port_id'] == $port->id)>{{ $port->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-700 mb-1">تاريخ الدخول</label>
                                        <input type="date" name="checkpoints[{{$index}}][entry_date]"
                                            value="{{ $checkpoint['entry_date'] }}"
                                            class="w-full rounded-md border-slate-300 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-700 mb-1">وقت الدخول</label>
                                        <input type="time" name="checkpoints[{{$index}}][entry_time]"
                                            value="{{ $checkpoint['entry_time'] }}"
                                            class="w-full rounded-md border-slate-300 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-700 mb-1">تاريخ الخروج</label>
                                        <input type="date" name="checkpoints[{{$index}}][exit_date]"
                                            value="{{ $checkpoint['exit_date'] }}"
                                            class="w-full rounded-md border-slate-300 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-700 mb-1">وقت الخروج</label>
                                        <input type="time" name="checkpoints[{{$index}}][exit_time]"
                                            value="{{ $checkpoint['exit_time'] }}"
                                            class="w-full rounded-md border-slate-300 text-sm">
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('local-shipments.index') }}"
                        class="px-4 py-2 border rounded-md text-slate-700 hover:bg-slate-50">إلغاء</a>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 shadow-sm">حفظ</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let checkpointIndex = {{ old('checkpoints') ? count(old('checkpoints')) : 0 }};

        function addCheckpoint() {
            const container = document.getElementById('checkpoints-container');
            const div = document.createElement('div');
            div.className = 'checkpoint-item grid gap-4 sm:grid-cols-5 p-4 bg-slate-50 rounded-lg border border-slate-200 relative animate-fade-in-down';

            div.innerHTML = `
                <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 left-2 text-red-500 hover:text-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">الجمرك</label>
                    <select name="checkpoints[${checkpointIndex}][customs_port_id]" class="w-full rounded-md border-slate-300 text-sm" required>
                        <option value="">اختر الجمرك</option>
                        @foreach($customsPorts as $port)
                            <option value="{{ $port->id }}">{{ $port->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">تاريخ الدخول</label>
                    <input type="date" name="checkpoints[${checkpointIndex}][entry_date]" class="w-full rounded-md border-slate-300 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">وقت الدخول</label>
                    <input type="time" name="checkpoints[${checkpointIndex}][entry_time]" class="w-full rounded-md border-slate-300 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">تاريخ الخروج</label>
                    <input type="date" name="checkpoints[${checkpointIndex}][exit_date]" class="w-full rounded-md border-slate-300 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">وقت الخروج</label>
                    <input type="time" name="checkpoints[${checkpointIndex}][exit_time]" class="w-full rounded-md border-slate-300 text-sm">
                </div>
            `;

            container.appendChild(div);
            checkpointIndex++;
        }
    </script>
</x-app-layout>
