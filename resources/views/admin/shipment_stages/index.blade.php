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
                                           class="text-sm text-blue-600 hover:text-blue-700">تعديل</a>
                                        <form method="POST" action="{{ route('admin.shipment-stages.destroy', $stage) }}" onsubmit="return confirm('حذف هذه المرحلة؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:text-red-700">حذف</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-6 text-center text-slate-500">لا توجد مراحل مسجلة بعد.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
