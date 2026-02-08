<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-slate-900 leading-tight">الشحن المحلي البري</h2>
                <p class="text-sm text-slate-500">إدارة الشحنات المحلية البرية.</p>
            </div>
            @can('manage shipments')
                <a href="{{ route('local-shipments.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    إضافة شحنة محلية برية
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('status'))
                <div class="bg-emerald-50 text-emerald-800 px-4 py-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <form method="GET" action="{{ route('local-shipments.index') }}"
                class="bg-white shadow-sm sm:rounded-xl p-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4 border border-slate-100">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-700" for="search">بحث</label>
                    <input id="search" name="search" type="text" value="{{ request('search') }}"
                        class="mt-2 w-full rounded-md border-slate-300" placeholder="رقم التسلسل، اللوحة، اسم المستخدم">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700" for="company_id">الشركة</label>
                    <select id="company_id" name="company_id" class="mt-2 w-full rounded-md border-slate-300">
                        <option value="">الكل</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" @selected(request('company_id') == $company->id)>{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700" for="section_id">القسم</label>
                    <select id="section_id" name="section_id" class="mt-2 w-full rounded-md border-slate-300">
                        <option value="">الكل</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" @selected(request('section_id') == $department->id)>{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700" for="date_from">تاريخ الوصول من</label>
                    <input id="date_from" name="date_from" type="date" value="{{ request('date_from') }}"
                        class="mt-2 w-full rounded-md border-slate-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700" for="date_to">تاريخ الوصول إلى</label>
                    <input id="date_to" name="date_to" type="date" value="{{ request('date_to') }}"
                        class="mt-2 w-full rounded-md border-slate-300">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">بحث</button>
                    <a href="{{ route('local-shipments.index') }}" class="px-4 py-2 border rounded-md text-slate-700">مسح</a>
                </div>
            </form>

            <div class="bg-white shadow-sm sm:rounded-xl p-6 overflow-x-auto border border-slate-100">
                <table class="min-w-full text-sm text-right">
                    <thead>
                    <tr class="text-slate-500 border-b">
                        <th class="py-2">رقم التسلسل</th>
                        <th class="py-2">رقم اللوحة</th>
                        <th class="py-2">اسم المستخدم</th>
                        <th class="py-2">الشركة</th>
                        <th class="py-2">القسم</th>
                        <th class="py-2">تاريخ الوصول</th>
                        <th class="py-2">الوجهة</th>
                        <th class="py-2">نوع البضاعة</th>
                        <th class="py-2">الحالة</th>
                        <th class="py-2">الإجراءات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($vehicles as $vehicle)
                        <tr class="border-b last:border-0">
                            <td class="py-2 font-semibold text-slate-800">{{ $vehicle->serial_number }}</td>
                            <td class="py-2">{{ $vehicle->vehicle_plate_number ?? '-' }}</td>
                            <td class="py-2">{{ $vehicle->user_name ?? '-' }}</td>
                            <td class="py-2">{{ $vehicle->company?->name ?? '-' }}</td>
                            <td class="py-2">{{ $vehicle->department?->name ?? '-' }}</td>
                            <td class="py-2">{{ $vehicle->arrival_date_from_branch?->format('Y-m-d') ?? '-' }}</td>
                            <td class="py-2">{{ $vehicle->destination ?? '-' }}</td>
                            <td class="py-2">{{ $vehicle->cargo_type ?? '-' }}</td>
                            <td class="py-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs {{ $vehicle->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $vehicle->is_active ? 'نشط' : 'غير نشط' }}
                                </span>
                            </td>
                            <td class="py-2">
                                <div class="flex items-center gap-2 justify-end">
                                    @can('manage shipments')
                                        <a href="{{ route('local-shipments.edit', $vehicle) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-blue-50 text-blue-600 hover:bg-blue-100"
                                            title="تعديل" aria-label="تعديل">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            <span class="sr-only">تعديل</span>
                                        </a>
                                        <form method="POST" action="{{ route('local-shipments.destroy', $vehicle) }}"
                                            onsubmit="return confirm('هل أنت متأكد من حذف الشحنة؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-rose-50 text-rose-600 hover:bg-rose-100"
                                                title="حذف" aria-label="حذف">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                <span class="sr-only">حذف</span>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-4 text-center text-slate-500">لا توجد شحنات بعد.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $vehicles->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
