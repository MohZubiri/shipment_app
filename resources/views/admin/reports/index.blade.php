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
                                    تقرير الشحنات</h5>
                                <p class="font-normal text-gray-700 text-sm">عرض وتصدير تقرير شامل عن الشحنات وحالتها.
                                </p>
                            </div>
                        </div>
                    </a>

                    <!-- Placeholder for future reports -->
                    <!--
                    <div class="block p-6 bg-gray-50 border border-gray-200 rounded-lg border-dashed">
                        <div class="flex items-center gap-4 opacity-50">
                            <div class="p-3 bg-gray-200 rounded-full text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <div>
                                <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-400">تقارير إضافية</h5>
                                <p class="font-normal text-gray-400 text-sm">قريباً...</p>
                            </div>
                        </div>
                    </div>
                    -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>