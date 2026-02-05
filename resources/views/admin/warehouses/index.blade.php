<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">إدارة المخازن</h2>
                <p class="text-sm text-slate-500">عرض وإدارة جميع المخازن</p>
            </div>
            <a href="{{ route('admin.warehouses.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                إضافة مخزن جديد
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 px-4 py-3 text-emerald-800 bg-emerald-50 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-xl border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    الاسم
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    الكود
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    الموقع
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    السعة
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    المراحل
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    الحالة
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    الإجراءات
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse($warehouses as $warehouse)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-slate-900">{{ $warehouse->name }}</div>
                                        @if($warehouse->address)
                                            <div class="text-xs text-slate-500">{{ Str::limit($warehouse->address, 50) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium bg-slate-100 text-slate-800 rounded-full">
                                            {{ $warehouse->code }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                        {{ $warehouse->location ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                        {{ $warehouse->capacity ?? 'غير محدد' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-slate-900">{{ $warehouse->stages_count }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($warehouse->is_active)
                                            <span class="px-2 py-1 text-xs font-medium bg-emerald-100 text-emerald-800 rounded-full">
                                                نشط
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                                غير نشط
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.warehouses.show', $warehouse) }}" class="text-blue-600 hover:text-blue-900 ml-3">
                                            عرض
                                        </a>
                                        <a href="{{ route('admin.warehouses.edit', $warehouse) }}" class="text-indigo-600 hover:text-indigo-900 ml-3">
                                            تعديل
                                        </a>
                                        <form action="{{ route('admin.warehouses.destroy', $warehouse) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('هل أنت متأكد من حذف هذا المخزن؟')">
                                                حذف
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="text-slate-500">
                                            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            <p class="mt-2">لا توجد مخازن حالياً</p>
                                            <a href="{{ route('admin.warehouses.create') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                                إضافة مخزن جديد
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($warehouses->hasPages())
                    <div class="bg-white px-4 py-3 border-t border-slate-200 sm:px-6">
                        {{ $warehouses->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
