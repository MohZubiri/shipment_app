<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">إضافة شحنة دولية برية</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('road-shipments.store') }}"
                class="bg-white shadow-sm sm:rounded-xl p-6 space-y-6 border border-slate-100">
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

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="operation_number">رقم العملية</label>
                        <input id="operation_number" name="operation_number" type="text" value="{{ old('operation_number') }}"
                            required class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="locomotive_number">رقم القاطرة</label>
                        <input id="locomotive_number" name="locomotive_number" type="text" value="{{ old('locomotive_number') }}"
                            class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="shipment_name">اسم الشحنة</label>
                        <input id="shipment_name" name="shipment_name" type="text" value="{{ old('shipment_name') }}"
                            class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="declaration_number">رقم البيان</label>
                        <input id="declaration_number" name="declaration_number" type="text" value="{{ old('declaration_number') }}"
                            class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="arrival_date">تاريخ الوصول</label>
                        <input id="arrival_date" name="arrival_date" type="date" value="{{ old('arrival_date') }}"
                            class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="exit_date">تاريخ الخروج</label>
                        <input id="exit_date" name="exit_date" type="date" value="{{ old('exit_date') }}"
                            class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="docking_days">أيام الربط</label>
                        <input id="docking_days" name="docking_days" type="number" min="0" value="{{ old('docking_days') }}"
                            class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="documents_sent_date">تاريخ إرسال المستندات</label>
                        <input id="documents_sent_date" name="documents_sent_date" type="date" value="{{ old('documents_sent_date') }}"
                            class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="documents_type">نوع المستندات</label>
                        <input id="documents_type" name="documents_type" type="text" value="{{ old('documents_type') }}"
                            class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="warehouse_arrival_date">تاريخ وصول المخزن</label>
                        <input id="warehouse_arrival_date" name="warehouse_arrival_date" type="date" value="{{ old('warehouse_arrival_date') }}"
                            class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('road-shipments.index') }}" class="px-4 py-2 border rounded-md text-slate-700">إلغاء</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
