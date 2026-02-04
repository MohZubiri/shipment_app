<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    تفاصيل الشحنة
                </h2>
                <p class="text-sm text-gray-500 mt-1">رقم داخلي: {{ $shipment->id }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.shipments.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    رجوع
                </a>
                @can('manage shipments')
                    <a href="{{ route('admin.shipments.edit', $shipment) }}"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        تعديل
                    </a>
                    <form method="POST" action="{{ route('admin.shipments.destroy', $shipment) }}"
                        onsubmit="return confirm('هل أنت متأكد من حذف هذه الشحنة؟ لا يمكن التراجع عن هذا الإجراء.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            حذف
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 p-6">
                <div class="grid gap-6 sm:grid-cols-2 text-sm">
                    <div>
                        <p class="text-gray-500">الاسم</p>
                        <p class="font-semibold text-gray-900">{{ $shipment->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">الكمية</p>
                        <p class="font-semibold text-gray-900">{{ $shipment->quantity }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">الحالة</p>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $shipment->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $shipment->status }}
                        </span>
                    </div>
                    <div>
                        <p class="text-gray-500">تاريخ الإنشاء</p>
                        <p class="font-semibold text-gray-900">{{ $shipment->created_at?->format('Y-m-d H:i') ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">آخر تعديل</p>
                        <p class="font-semibold text-gray-900">{{ $shipment->updated_at?->format('Y-m-d H:i') ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
