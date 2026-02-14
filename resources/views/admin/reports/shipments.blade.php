<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('تقرير الشحنات البحرية') }}
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
                        <a id="pdf_export_link" href="{{ route('admin.reports.shipments.pdf', array_merge(request()->all(), ['font' => request('font') ?: 'dejavusans'])) }}"
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
                <form method="GET" action="{{ route('admin.reports.shipments') }}"
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
                        <x-input-label for="shipping_line_type" :value="__('نوع الخط الملاحي')" class="mb-1" />
                        <select id="shipping_line_type" name="shipping_line_type"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full">
                            <option value="">{{ __('الكل') }}</option>
                            <option value="sea" @selected(request('shipping_line_type') === 'sea')>بحري</option>
                            <option value="air" @selected(request('shipping_line_type') === 'air')>جوي</option>
                            <option value="land" @selected(request('shipping_line_type') === 'land')>بري</option>
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
                    @if(request()->filled('date_from') || request()->filled('date_to') || request()->filled('department_id') || request()->filled('customs_port_id') || request()->filled('company_id') || request()->filled('shipping_line_type'))
                        <div>
                            <a href="{{ route('admin.reports.shipments') }}"
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
                            تقرير الشحنات 
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
                            @if(request('shipping_line_type'))
                                <span class="mx-2">|</span>
                                <span>نوع الخط الملاحي: {{ request('shipping_line_type') === 'sea' ? 'بحري' : (request('shipping_line_type') === 'air' ? 'جوي' : 'بري') }}</span>
                            @endif
                        </div>
                    </div>

                    <table class="w-full text-sm text-center text-gray-500 border-collapse border border-gray-300">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-2 py-3 border border-gray-300">الرقم</th>
                                <th scope="col" class="px-2 py-3 border border-gray-300">رقم العملية</th>
                                <th scope="col" class="px-2 py-3 border border-gray-300">إسم الشحنة</th>
                                <th scope="col" class="px-2 py-3 border border-gray-300">رقم البوليصة</th>
                                <th scope="col" class="px-2 py-3 border border-gray-300">رقم الببان</th>
                                <th scope="col" class="px-2 py-3 border border-gray-300">حالة الببان</th>
                                <th scope="col" class="px-2 py-3 border border-gray-300">الخط الملاحي</th>
                                <th scope="col" class="px-2 py-3 border border-gray-300">نوع الخط الملاحي</th>
                                <th scope="col" class="px-2 py-3 border border-gray-300">عدد الحاويات</th>
                                <th scope="col" class="px-2 py-3 border border-gray-300">تاريخ وصول الباخرة المتوقعة</th>
                                <th scope="col" class="px-2 py-3 border border-gray-300">فترة السماح</th>
                                <th scope="col" class="px-2 py-3 border border-gray-300">تاريخ انتهاء فترة السماح</th>
                                <th scope="col" class="px-2 py-3 border border-gray-300">نوع المستندات</th>
                                <th scope="col" class="px-2 py-3 border border-gray-300">تاريخ استلام المستندات</th>
                                <th scope="col" class="px-2 py-3 border border-gray-300">مرحلة الشحنة الحالية</th>
                               
                                <th scope="col" class="px-2 py-3 border border-gray-300">وجهة الترحيل</th>
                                  <th scope="col" class="px-2 py-3 border border-gray-300">تاريخ الترحيل</th>
                              
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($shipments as $index => $shipment)
                                @php
                                    $shippingLineType = match ($shipment->shippingLine?->transport_type) {
                                        'sea' => 'بحري',
                                        'air' => 'جوي',
                                        'land' => 'بري',
                                        default => '-',
                                    };
                                @endphp
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-2 py-2 border border-gray-300 font-medium text-gray-900">{{ $index + 1 }}</td>
                                    <td class="px-2 py-2 border border-gray-300">{{ $shipment->operationno }}</td>
                                    <td class="px-2 py-2 border border-gray-300">{{ $shipment->shippmintno ?? '-' }}</td>
                                    <td class="px-2 py-2 border border-gray-300">{{ $shipment->pillno ?: '-' }}</td>
                                 
                                    <td class="px-2 py-2 border border-gray-300">{{ $shipment->datano }}</td>
                                   <td class="px-2 py-2 border border-gray-300">{{ $shipment->customsData ? ($shipment->customsData->state == 1 ? 'ضمان ' : 'سداد') : '-' }}</td> 
                                    <td class="px-2 py-2 border border-gray-300">
                                        {{ optional($shipment->shippingLine)->name ?? '-' }}
                                    </td>
                                    <td class="px-2 py-2 border border-gray-300">
                                        {{ $shippingLineType }}
                                    </td>
                                    @php
                                        $containerCounts = $shipment->containers
                                            ->filter(fn($container) => $container->container_size && $container->container_count)
                                            ->groupBy('container_size')
                                            ->map(fn($group) => $group->sum('container_count'));

                                        if ($containerCounts->isEmpty()) {
                                            $containerCounts = collect();
                                            if (($shipment->park40 ?? 0) > 0) {
                                                $containerCounts->put('40', $shipment->park40);
                                            }
                                            if (($shipment->park20 ?? 0) > 0) {
                                                $containerCounts->put('20', $shipment->park20);
                                            }
                                        }

                                        $preferredOrder = ['40', '40HC', '20'];
                                        $orderedCounts = collect();
                                        foreach ($preferredOrder as $size) {
                                            if ($containerCounts->has($size)) {
                                                $orderedCounts->put($size, $containerCounts->get($size));
                                            }
                                        }
                                        $orderedCounts = $orderedCounts->union($containerCounts->diffKeys($orderedCounts));
                                        $unitLabel = $shipment->shippingLine?->transport_type === 'air' ? 'طرود' : 'حاويات';
                                        $containerSummary = $orderedCounts->map(fn($count, $size) => "{$unitLabel} {$size} قدم عدد {$count}")->implode('<br>');
                                    @endphp
                                    <td class="px-2 py-2 border border-gray-300 text-sm font-semibold text-gray-800">
                                        {!! $containerSummary !== '' ? $containerSummary : '-' !!}
                                    </td>
                                    <td class="px-2 py-2 border border-gray-300">
                                        {{ $shipment->dategase ? $shipment->dategase->format('Y-m-d') : '-' }}
                                    </td>
                                    <td class="px-2 py-2 border border-gray-300">
                                        {{ $shipment->shippingLine->time ?? '-' }}
                                    </td>
                                    <!-- Confirm field for allowance period -->
                                    <td class="px-2 py-2 border border-gray-300">
                                        {{ $shipment->endallowdate ? $shipment->endallowdate->subDay()->format('Y-m-d') : '-' }}
                                    </td>
                                    <td class="px-2 py-2 border border-gray-300">
                                        {{ $shipment->paperno ?: '-' }}
                                    </td>
                                    <td class="px-2 py-2 border border-gray-300">
                                        {{ $shipment->officedate ? $shipment->officedate->format('Y-m-d') : '-' }}
                                    </td>
                                     
                                     <td class="px-2 py-2 border border-gray-300">{{ $shipment->currentStage->name ?? '-' }}</td>
                                    <td class="px-2 py-2 border border-gray-300">
                                        @php
                                            $relayDestination = $shipment->relayname ?: $shipment->warehouseTracking?->warehouse?->name;
                                        @endphp
                                        {{ $relayDestination ?? '-' }}
                                    </td>
                                     <td class="px-2 py-2 border border-gray-300">
                                        @php
                                            $relayDate = $shipment->relaydate ?? $shipment->warehouseTracking?->event_date;
                                        @endphp
                                        {{ $relayDate ? $relayDate->format('Y-m-d') : '-' }}
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="17" class="px-6 py-4 text-center text-gray-500">
                                        لا يوجد شحنات لعرضها
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-12 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 mb-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">يرجى استخدام الفلاتر أعلاه للبحث</h3>
                    <p class="text-gray-500">قم باختيار المنفذ، القسم، الشركة، أو التواريخ لعرض تقرير الشحنات</p>
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
