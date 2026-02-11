<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('تقرير الشحنات البرية') }}
            </h2>
            <div class="flex gap-2">
                @if($hasFilters)
                    <div class="flex items-center gap-2">
                        <select id="pdf_font" name="pdf_font"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                            onchange="updatePdfLink()">
                            <option value="dejavusans" {{ request('font') == 'dejavusans' || !request('font') ? 'selected' : '' }}>DejaVu Sans</option>
                            <option value="cairo" {{ request('font') == 'cairo' ? 'selected' : '' }}>Cairo</option>
                            <option value="almarai" {{ request('font') == 'almarai' ? 'selected' : '' }}>Almarai</option>
                        </select>
                        <a id="pdf_export_link" href="{{ route('admin.reports.land_shipping.pdf', array_merge(request()->all(), ['font' => request('font') ?: 'dejavusans'])) }}"
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            {{ __('تصدير PDF') }}
                        </a>
                    </div>
                @endif
                <a href="{{ route('admin.reports.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('رجوع') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-6 mb-6">
                <form method="GET" action="{{ route('admin.reports.land_shipping') }}"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-7 gap-4 items-end">
                    <div>
                        <x-input-label for="customs_port_id" :value="__('المنفذ')" class="mb-1" />
                        <select id="customs_port_id" name="customs_port_id"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full">
                            <option value="">{{ __('الكل') }}</option>
                            @foreach($ports as $port)
                                <option value="{{ $port->id }}" {{ request('customs_port_id') == $port->id ? 'selected' : '' }}>
                                    {{ $port->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="department_id" :value="__('القسم')" class="mb-1" />
                        <select id="department_id" name="department_id"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full">
                            <option value="">{{ __('الكل') }}</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="company_id" :value="__('الشركة')" class="mb-1" />
                        <select id="company_id" name="company_id"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full">
                            <option value="">{{ __('الكل') }}</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="date_from" :value="__('من تاريخ')" class="mb-1" />
                        <x-text-input id="date_from" class="block w-full" type="date" name="date_from"
                            :value="request('date_from')" />
                    </div>
                    <div>
                        <x-input-label for="date_to" :value="__('إلى تاريخ')" class="mb-1" />
                        <x-text-input id="date_to" class="block w-full" type="date" name="date_to"
                            :value="request('date_to')" />
                    </div>
                    <div>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 h-[42px]">
                            {{ __('تصفية') }}
                        </button>
                    </div>
                    @if($hasFilters)
                        <div>
                            <a href="{{ route('admin.reports.land_shipping') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 h-[42px]">
                                {{ __('مسح التصفية') }}
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            @if($hasFilters)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 overflow-x-auto">
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">
                            تقرير الشحنات البرية
                        </h3>
                        <div class="text-sm text-gray-600">
                            @if(request('company_id'))
                                <span>الشركة: {{ $companies->where('id', request('company_id'))->first()->name ?? '' }}</span>
                            @endif
                            @if(request('department_id'))
                                <span class="mx-2">|</span>
                                <span>القسم: {{ $departments->where('id', request('department_id'))->first()->name ?? '' }}</span>
                            @endif
                            @if(request('customs_port_id'))
                                <span class="mx-2">|</span>
                                <span>المنفذ الجمركي: {{ $ports->where('id', request('customs_port_id'))->first()->name ?? '' }}</span>
                            @endif
                        </div>
                    </div>

                    <table class="w-full text-sm text-center text-gray-500 border-collapse border border-gray-300">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-2 py-3 border border-gray-300">الرقم</th>
                                <th scope="col" class="px-2 py-3 border border-gray-300">رقم العملية</th>
                                <th scope="col" class="px-2 py-3 border border-gray-300">أرقام القواطر</th>
                                <th scope="col" class="px-2 py-3 border border-gray-300">اسم الشحنة</th>
                                <th scope="col" class="px-2 py-3 border border-gray-300">عدد القواطر</th>
                                <th scope="col" class="px-2 py-3 border border-gray-300">رقم البيان</th>
                                <th scope="col" class="px-2 py-3 border border-gray-300">حالة البيان</th>
                                <th scope="col" class="px-2 py-3 border border-gray-300">تاريخ الوصول</th>
                                <th scope="col" class="px-2 py-3 border border-gray-300">تاريخ الخروج</th>
                                <th scope="col" class="px-2 py-3 border border-gray-300">الشركة</th>
                                <th scope="col" class="px-2 py-3 border border-gray-300">القسم</th>
                                <th scope="col" class="px-2 py-3 border border-gray-300">المرحلة الحالية</th>
                                <th scope="col" class="px-2 py-3 border border-gray-300">وجهة الترحيل</th>
                                <th scope="col" class="px-2 py-3 border border-gray-300">تاريخ استلام المستندات</th>
                                <th scope="col" class="px-2 py-3 border border-gray-300">أيام المماسي</th>
                                <th scope="col" class="px-2 py-3 border border-gray-300">نوع المستند</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($landShipments as $index => $landShipping)
                                @php
                                    // Calculate docking days (أيام المماسي)
                                    $dockingDays = '-';
                                    if ($landShipping->arrival_date && $landShipping->exit_date) {
                                        $diffDays = $landShipping->arrival_date->diffInDays($landShipping->exit_date, false);
                                        // If difference is 2 days or more, start counting after 2 days
                                        if ($diffDays >= 2) {
                                            $dockingDays = $diffDays - 2;
                                        } else {
                                            $dockingDays = 0;
                                        }
                                    }
                                @endphp
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-2 py-2 border border-gray-300 font-medium text-gray-900 text-xs">{{ $index + 1 }}</td>
                                    <td class="px-2 py-2 border border-gray-300 text-xs whitespace-nowrap">{{ $landShipping->operation_number ?? '-' }}</td>
                                    <td class="px-2 py-2 border border-gray-300 text-xs whitespace-nowrap">{{ $landShipping->locomotives->pluck('locomotive_number')->implode(', ') ?: '-' }}</td>
                                    <td class="px-2 py-2 border border-gray-300 text-xs whitespace-nowrap">{{ $landShipping->shipment_name ?? '-' }}</td>
                                    <td class="px-2 py-2 border border-gray-300 text-xs whitespace-nowrap">{{ $landShipping->locomotives->count() ?: '-' }}</td>
                                    <td class="px-2 py-2 border border-gray-300 text-xs whitespace-nowrap">{{ $landShipping->declaration_number ?? '-' }}</td>
                                    <td class="px-2 py-2 border border-gray-300 text-xs whitespace-nowrap">{{ $landShipping->documents_type ?? '-' }}</td>
                                    <td class="px-2 py-2 border border-gray-300 text-xs">
                                        {{ $landShipping->arrival_date ? $landShipping->arrival_date->format('Y-m-d') : '-' }}
                                    </td>
                                    <td class="px-2 py-2 border border-gray-300 text-xs">
                                        {{ $landShipping->exit_date ? $landShipping->exit_date->format('Y-m-d') : '-' }}
                                    </td>
                                    <td class="px-2 py-2 border border-gray-300 text-xs">
                                        {{ optional($landShipping->company)->name ?? '-' }}
                                    </td>
                                    <td class="px-2 py-2 border border-gray-300 text-xs">
                                        {{ optional($landShipping->department)->name ?? '-' }}
                                    </td>
                                    <td class="px-2 py-2 border border-gray-300 text-xs">
                                        {{ optional($landShipping->currentStage)->name ?? '-' }}
                                    </td>
                                    <td class="px-2 py-2 border border-gray-300 text-xs">
                                        @if(optional($landShipping->currentStage)->code === 'warehouse')
                                            {{ $landShipping->warehouseTracking?->warehouse?->name ?? '-' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-2 py-2 border border-gray-300 text-xs">
                                        {{ $landShipping->documents_sent_date ? $landShipping->documents_sent_date->format('Y-m-d') : '-' }}
                                    </td>
                                    <td class="px-2 py-2 border border-gray-300 text-xs">
                                        {{ $dockingDays }}
                                    </td>
                                    <td class="px-2 py-2 border border-gray-300 text-xs">
                                        {{ $landShipping->attachedDocuments->pluck('name')->implode(', ') ?: '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="16" class="px-6 py-4 text-center text-gray-500">
                                        لا يوجد بيانات تطابق الفلاتر المختارة
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-12 text-center text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <p class="text-lg">{{ __('يرجى اختيار الفلاتر لعرض نتائج التقرير') }}</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        function updatePdfLink() {
            const fontSelect = document.getElementById('pdf_font');
            const pdfLink = document.getElementById('pdf_export_link');
            const currentUrl = new URL(pdfLink.href);
            currentUrl.searchParams.set('font', fontSelect.value);
            pdfLink.href = currentUrl.toString();
        }
    </script>
</x-app-layout>
