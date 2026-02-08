<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('تقرير الشحنات') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.reports.shipments.pdf', request()->all()) }}"
                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    {{ __('تصدير PDF') }}
                </a>
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
                    class="flex flex-wrap items-end gap-4">
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
                        <x-input-label for="shipment_name" :value="__('اسم الشحنة')" class="mb-1" />
                        <x-text-input id="shipment_name" class="block w-full" type="text" name="shipment_name"
                            :value="request('shipment_name')" />
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
                    @if(request()->filled('date_from') || request()->filled('date_to') || request()->filled('department_id') || request()->filled('customs_port_id') || request()->filled('shipment_name'))
                        <div>
                            <a href="{{ route('admin.reports.shipments') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 h-[42px]">
                                {{ __('مسح التصفية') }}
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 overflow-x-auto">
                <div class="p-6 text-center">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">
                        التقرير اليومي لشركة التكامل الدولية قسم
                        {{ request('department_id') ? $departments->where('id', request('department_id'))->first()->name ?? 'العصائر' : 'العصائر' }}
                        لدى منفذ
                    </h3>
                    <h4 class="text-lg font-semibold text-gray-700">
                        {{ request('customs_port_id') ? $ports->where('id', request('customs_port_id'))->first()->name ?? 'منفذ شحن' : 'منفذ شحن' }}
                    </h4>
                </div>

                <table class="w-full text-sm text-center text-gray-500 border-collapse border border-gray-300">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-2 py-3 border border-gray-300">الرقم</th>
                            <th scope="col" class="px-2 py-3 border border-gray-300">رقم العملية</th>
                            <th scope="col" class="px-2 py-3 border border-gray-300">إسم الشحنة</th>
                            <th scope="col" class="px-2 py-3 border border-gray-300">رقم البوليصة</th>
                            <th scope="col" class="px-2 py-3 border border-gray-300">رقم الببان</th>
                            <th scope="col" class="px-2 py-3 border border-gray-300">الخط الملاحي</th>
                            <th scope="col" class="px-2 py-3 border border-gray-300">حاويات 20</th>
                            <th scope="col" class="px-2 py-3 border border-gray-300">حاويات 40</th>
                            <th scope="col" class="px-2 py-3 border border-gray-300">تاريخ وصول الباخرة المتوقعة</th>
                            <th scope="col" class="px-2 py-3 border border-gray-300">فترة السماح</th>
                            <th scope="col" class="px-2 py-3 border border-gray-300">تاريخ انتهاء فترة السماح</th>
                            <th scope="col" class="px-2 py-3 border border-gray-300">نوع المستندات</th>
                            <th scope="col" class="px-2 py-3 border border-gray-300">تاريخ استلام المستندات</th>
                            <th scope="col" class="px-2 py-3 border border-gray-300">تاريخ الترحيل</th>
                            <th scope="col" class="px-2 py-3 border border-gray-300">وجهة الترحيل</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shipments as $index => $shipment)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-2 py-2 border border-gray-300 font-medium text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-2 py-2 border border-gray-300">{{ $shipment->operationno }}</td>
                                <td class="px-2 py-2 border border-gray-300">{{ $shipment->shippmintno ?? '-' }}</td>
                                <td class="px-2 py-2 border border-gray-300">{{ $shipment->pilno }}</td>
                                <td class="px-2 py-2 border border-gray-300">{{ $shipment->datano }}</td>
                                <td class="px-2 py-2 border border-gray-300">
                                    {{ optional($shipment->shippingLine)->name ?? '-' }}
                                </td>
                                <td class="px-2 py-2 border border-gray-300">{{ $shipment->park20 }}</td>
                                <td class="px-2 py-2 border border-gray-300">{{ $shipment->park40 }}</td>
                                <td class="px-2 py-2 border border-gray-300">
                                    {{ $shipment->dategase ? $shipment->dategase->format('Y-m-d') : '-' }}
                                </td>
                                <td class="px-2 py-2 border border-gray-300">{{ $shipment->others ?? '-' }}</td>
                                <!-- Confirm field for allowance period -->
                                <td class="px-2 py-2 border border-gray-300">
                                    {{ $shipment->endallowdate ? $shipment->endallowdate->format('Y-m-d') : '-' }}
                                </td>
                                <td class="px-2 py-2 border border-gray-300">{{ $shipment->paperno }}</td>
                                <td class="px-2 py-2 border border-gray-300">
                                    {{ $shipment->officedate ? $shipment->officedate->format('Y-m-d') : '-' }}
                                </td>
                                <td class="px-2 py-2 border border-gray-300">
                                    {{ $shipment->relaydate ? $shipment->relaydate->format('Y-m-d') : '-' }}
                                </td>
                                <td class="px-2 py-2 border border-gray-300">{{ $shipment->relayname }}</td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="15" class="px-6 py-4 text-center text-gray-500">
                                    لا يوجد شحنات لعرضها
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
