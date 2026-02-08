<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-slate-900 leading-tight">إدارة البيانات الجمركية</h2>
                <p class="text-sm text-slate-500">متابعة بيانات جدول البيان الجمركي وربطها بالشحنات.</p>
            </div>
            @can('manage customs')
                <a href="{{ route('customs.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    بيان جديد
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('status'))
                <div class="bg-emerald-50 text-emerald-800 px-4 py-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <form method="GET" action="{{ route('customs.index') }}" class="bg-white shadow-sm sm:rounded-xl p-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4 border border-slate-100">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-700" for="search">بحث برقم البيان</label>
                    <input id="search" name="search" type="text" value="{{ request('search') }}" class="mt-2 w-full rounded-md border-slate-300" placeholder="مثال: 12345">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700" for="date_from">من تاريخ</label>
                    <input id="date_from" name="date_from" type="date" value="{{ request('date_from') }}" class="mt-2 w-full rounded-md border-slate-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700" for="date_to">إلى تاريخ</label>
                    <input id="date_to" name="date_to" type="date" value="{{ request('date_to') }}" class="mt-2 w-full rounded-md border-slate-300">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">بحث</button>
                    <a href="{{ route('customs.index') }}" class="px-4 py-2 border rounded-md text-slate-700">مسح</a>
                </div>
            </form>

            <div class="bg-white shadow-sm sm:rounded-xl p-6 border border-slate-100 overflow-x-auto">
                <table class="min-w-full text-sm text-right">
                    <thead>
                    <tr class="text-slate-500 border-b">
                        <th class="py-2">رقم البيان</th>
                        <th class="py-2">تاريخ البيان</th>
                        <th class="py-2">الحالة</th>
                        <th class="py-2">عدد الشحنات</th>
                        <th class="py-2">إجراءات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($dataRows as $row)
                        @php
                            $stateLabel = match ($row->state) {
                                1 => 'ضمان',
                                2 => 'سداد',
                                default => 'غير محددة',
                            };
                        @endphp
                        <tr class="border-b last:border-0">
                            <td class="py-2 font-semibold text-slate-800">{{ $row->datano }}</td>
                            <td class="py-2">{{ $row->datacreate?->format('Y-m-d') }}</td>
                            <td class="py-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs {{ $row->state ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $stateLabel }}
                                </span>
                            </td>
                            <td class="py-2">{{ $row->shipments_count }}</td>
                            <td class="py-2 flex items-center gap-2 justify-end">
                                @can('manage customs')
                                    <a href="{{ route('customs.edit', $row) }}"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-blue-50 text-blue-600 hover:bg-blue-100"
                                        title="تعديل" aria-label="تعديل">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        <span class="sr-only">تعديل</span>
                                    </a>
                                    <form method="POST" action="{{ route('customs.destroy', $row) }}" onsubmit="return confirm('هل تريد حذف البيان الجمركي؟')">
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
                                @else
                                    <span class="text-slate-400">عرض فقط</span>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-slate-500">لا توجد بيانات جمركية بعد.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $dataRows->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
