<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">مراحل الشحنة</h2>
                <p class="text-sm text-slate-500">إدارة مراحل الرحلة الكاملة للشحنة</p>
            </div>
            <a href="{{ route('admin.shipment-stages.create') }}"
               class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg shadow hover:bg-blue-700">
                إضافة مرحلة
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-50 border border-green-100 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <div class="overflow-hidden bg-white shadow-sm border border-slate-100 sm:rounded-xl">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr class="text-right text-slate-600">
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">الاسم</th>
                            <th class="px-4 py-3">الكود</th>
                            <th class="px-4 py-3">النوع</th>
                            <th class="px-4 py-3">الترتيب</th>
                            <th class="px-4 py-3">اللون</th>
                            <th class="px-4 py-3">يتطلب حاويات</th>
                            <th class="px-4 py-3">يتطلب مخزن</th>
                            <th class="px-4 py-3">الحالة</th>
                            <th class="px-4 py-3 text-left">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($stages as $stage)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 font-medium text-slate-900">{{ $stage->id }}</td>
                                <td class="px-4 py-3 text-slate-900">{{ $stage->name }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ $stage->code }}</td>
                                <td class="px-4 py-3 text-slate-500">
                                    @php
                                        $stageTypeLabel = match ($stage->applies_to) {
                                            'sea' => 'بحري',
                                            'land' => 'بري',
                                            default => 'كلاهما',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-600">
                                        {{ $stageTypeLabel }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-slate-500">{{ $stage->order }}</td>
                                <td class="px-4 py-3">
                                    @if($stage->color)
                                        <span class="inline-flex items-center gap-2">
                                            <span class="inline-block w-4 h-4 rounded-full" style="background-color: {{ $stage->color }}"></span>
                                            <span class="text-xs text-slate-500">{{ $stage->color }}</span>
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full {{ $stage->needs_containers ? 'bg-amber-50 text-amber-700' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $stage->needs_containers ? 'نعم' : 'لا' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full {{ $stage->needs_warehouse ? 'bg-blue-50 text-blue-700' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $stage->needs_warehouse ? 'نعم' : 'لا' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full {{ $stage->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $stage->is_active ? 'نشط' : 'متوقف' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-left">
                                    <div class="flex items-center gap-2 justify-end">
                                        <a href="{{ route('admin.shipment-stages.edit', $stage) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-blue-50 text-blue-600 hover:bg-blue-100"
                                           title="تعديل" aria-label="تعديل">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            <span class="sr-only">تعديل</span>
                                        </a>
                                        <form method="POST" action="{{ route('admin.shipment-stages.destroy', $stage) }}" onsubmit="return confirm('حذف هذه المرحلة؟');">
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
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-4 py-6 text-center text-slate-500">لا توجد مراحل مسجلة بعد.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
