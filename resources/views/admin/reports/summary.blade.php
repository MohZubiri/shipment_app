<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('التقرير المجمّع حسب الشركة') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.reports.summary.pdf', request()->all()) }}"
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
                <form method="GET" action="{{ route('admin.reports.summary') }}"
                    class="flex flex-wrap items-end gap-4">
                    <div>
                        <x-input-label for="company_id" :value="__('الشركة')" class="mb-1" />
                        <select id="company_id" name="company_id"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full">
                            <option value="">{{ __('الكل') }}</option>
                            @foreach($allCompanies as $company)
                                <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 h-[42px]">
                            {{ __('تصفية') }}
                        </button>
                    </div>
                    @if(request()->filled('company_id'))
                        <div>
                            <a href="{{ route('admin.reports.summary') }}"
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
                        التقرير المجمّع حسب الشركة
                    </h3>
                </div>

                <table class="w-full text-sm text-center text-gray-500 border-collapse border border-gray-300">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-2 py-3 border border-gray-300">الرقم</th>
                            <th scope="col" class="px-2 py-3 border border-gray-300">الشركة</th>
                            <th scope="col" class="px-2 py-3 border border-gray-300">عدد الشحنات (بحر/جو)</th>
                            <th scope="col" class="px-2 py-3 border border-gray-300">عدد الشحنات البرية</th>
                            <th scope="col" class="px-2 py-3 border border-gray-300">عدد مركبات الجمارك المحلية</th>
                            <th scope="col" class="px-2 py-3 border border-gray-300">الإجمالي</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($companies as $index => $company)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-2 py-2 border border-gray-300 font-medium text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-2 py-2 border border-gray-300 font-semibold">{{ $company->name }}</td>
                                <td class="px-2 py-2 border border-gray-300">{{ $company->shipment_transactions_count ?? 0 }}</td>
                                <td class="px-2 py-2 border border-gray-300">{{ $company->land_shippings_count ?? 0 }}</td>
                                <td class="px-2 py-2 border border-gray-300">{{ $company->local_customs_vehicles_count ?? 0 }}</td>
                                <td class="px-2 py-2 border border-gray-300 font-bold">
                                    {{ ($company->shipment_transactions_count ?? 0) + ($company->land_shippings_count ?? 0) + ($company->local_customs_vehicles_count ?? 0) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    لا يوجد شركات لعرضها
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-100 font-bold">
                        <tr>
                            <td colspan="2" class="px-2 py-2 border border-gray-300 text-left">الإجمالي الكلي</td>
                            <td class="px-2 py-2 border border-gray-300">{{ $companies->sum('shipment_transactions_count') }}</td>
                            <td class="px-2 py-2 border border-gray-300">{{ $companies->sum('land_shippings_count') }}</td>
                            <td class="px-2 py-2 border border-gray-300">{{ $companies->sum('local_customs_vehicles_count') }}</td>
                            <td class="px-2 py-2 border border-gray-300">
                                {{ $companies->sum('shipment_transactions_count') + $companies->sum('land_shippings_count') + $companies->sum('local_customs_vehicles_count') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
