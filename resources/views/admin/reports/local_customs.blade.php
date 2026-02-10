<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('تقرير مركبات الجمارك المحلية') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.reports.local_customs.pdf', request()->all()) }}"
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
                <form method="GET" action="{{ route('admin.reports.local_customs') }}"
                    class="flex flex-wrap items-end gap-4">
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
                        <x-input-label for="section_id" :value="__('القسم')" class="mb-1" />
                        <select id="section_id" name="section_id"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full">
                            <option value="">{{ __('الكل') }}</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                    {{ $section->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="search" :value="__('بحث')" class="mb-1" />
                        <x-text-input id="search" class="block w-full" type="text" name="search"
                            :value="request('search')" placeholder="الرقم التسلسلي، رقم اللوحة، اسم المستخدم" />
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
                    @if(request()->filled('date_from') || request()->filled('date_to') || request()->filled('company_id') || request()->filled('section_id') || request()->filled('search'))
                        <div>
                            <a href="{{ route('admin.reports.local_customs') }}"
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
                        تقرير مركبات الجمارك المحلية
                        {{ request('company_id') ? $companies->where('id', request('company_id'))->first()->name ?? '' : '' }}
                    </h3>
                </div>

                <table class="w-full text-sm text-center text-gray-500 border-collapse border border-gray-300">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-2 py-3 border border-gray-300">الرقم</th>
                            <th scope="col" class="px-2 py-3 border border-gray-300">الرقم التسلسلي</th>
                            <th scope="col" class="px-2 py-3 border border-gray-300">رقم اللوحة</th>
                            <th scope="col" class="px-2 py-3 border border-gray-300">اسم المستخدم</th>
                            <th scope="col" class="px-2 py-3 border border-gray-300">تاريخ الوصول من الفرع</th>
                            <th scope="col" class="px-2 py-3 border border-gray-300">الشركة</th>
                            <th scope="col" class="px-2 py-3 border border-gray-300">القسم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vehicles as $index => $vehicle)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-2 py-2 border border-gray-300 font-medium text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-2 py-2 border border-gray-300">{{ $vehicle->serial_number ?? '-' }}</td>
                                <td class="px-2 py-2 border border-gray-300">{{ $vehicle->vehicle_plate_number ?? '-' }}</td>
                                <td class="px-2 py-2 border border-gray-300">{{ $vehicle->user_name ?? '-' }}</td>
                                <td class="px-2 py-2 border border-gray-300">
                                    {{ $vehicle->arrival_date_from_branch ? $vehicle->arrival_date_from_branch->format('Y-m-d') : '-' }}
                                </td>
                                <td class="px-2 py-2 border border-gray-300">
                                    {{ optional($vehicle->company)->name ?? '-' }}
                                </td>
                                <td class="px-2 py-2 border border-gray-300">
                                    {{ optional($vehicle->department)->name ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    لا يوجد مركبات لعرضها
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
