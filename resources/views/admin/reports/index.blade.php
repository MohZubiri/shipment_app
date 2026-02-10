<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('التقارير') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Shipment Report Card -->
                    <a href="{{ route('admin.reports.shipments') }}"
                        class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition duration-150 ease-in-out group">
                        <div class="flex items-center gap-4">
                            <div
                                class="p-3 bg-indigo-100 rounded-full text-indigo-600 group-hover:bg-indigo-200 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h5
                                    class="mb-2 text-xl font-bold tracking-tight text-gray-900 group-hover:text-indigo-600">
                                    تقرير الشحنات (بحر/جو)</h5>
                                <p class="font-normal text-gray-700 text-sm">عرض وتصدير تقرير شامل عن الشحنات البحرية والجوية.
                                </p>
                            </div>
                        </div>
                    </a>

                    <!-- Land Shipping Report Card -->
                    <a href="{{ route('admin.reports.land_shipping') }}"
                        class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition duration-150 ease-in-out group">
                        <div class="flex items-center gap-4">
                            <div
                                class="p-3 bg-green-100 rounded-full text-green-600 group-hover:bg-green-200 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                            </div>
                            <div>
                                <h5
                                    class="mb-2 text-xl font-bold tracking-tight text-gray-900 group-hover:text-green-600">
                                    تقرير الشحنات البرية</h5>
                                <p class="font-normal text-gray-700 text-sm">عرض وتصدير تقرير شامل عن الشحنات البرية.
                                </p>
                            </div>
                        </div>
                    </a>

                    <!-- Local Customs Vehicles Report Card -->
                    <a href="{{ route('admin.reports.local_customs') }}"
                        class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition duration-150 ease-in-out group">
                        <div class="flex items-center gap-4">
                            <div
                                class="p-3 bg-orange-100 rounded-full text-orange-600 group-hover:bg-orange-200 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                </svg>
                            </div>
                            <div>
                                <h5
                                    class="mb-2 text-xl font-bold tracking-tight text-gray-900 group-hover:text-orange-600">
                                    تقرير مركبات الجمارك المحلية</h5>
                                <p class="font-normal text-gray-700 text-sm">عرض وتصدير تقرير شامل عن مركبات الجمارك المحلية.
                                </p>
                            </div>
                        </div>
                    </a>

                    <!-- Summary Report Card -->
                    <a href="{{ route('admin.reports.summary') }}"
                        class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition duration-150 ease-in-out group">
                        <div class="flex items-center gap-4">
                            <div
                                class="p-3 bg-purple-100 rounded-full text-purple-600 group-hover:bg-purple-200 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <div>
                                <h5
                                    class="mb-2 text-xl font-bold tracking-tight text-gray-900 group-hover:text-purple-600">
                                    التقرير المجمّع حسب الشركة</h5>
                                <p class="font-normal text-gray-700 text-sm">عرض ملخص شامل لجميع أنواع الشحنات حسب الشركة.
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>