<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-slate-900 leading-tight">
                    تفاصيل الشحنة
                </h2>
                <p class="text-sm text-slate-500 mt-1">رقم العملية: {{ $shipment->operationno }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('shipments.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    رجوع
                </a>
                <a href="{{ route('shipments.tracking.index', $shipment) }}"
                    class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    تتبع الشحنة
                </a>
                @can('manage shipments')
                    <a href="{{ route('shipments.edit', $shipment) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        تعديل
                    </a>
                    <form method="POST" action="{{ route('shipments.destroy', $shipment) }}"
                        onsubmit="return confirm('هل أنت متأكد من حذف الشحنة؟');">
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <section class="space-y-4 border border-slate-100 rounded-xl p-6 bg-white shadow-sm">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900">البيانات الأساسية</h3>
                    <span class="text-xs text-slate-400">Step 1</span>
                </div>
                @php
                    $customsStateLabel = match ($shipment->customsData?->state) {
                        1 => 'ضمان',
                        2 => 'سداد',
                        default => '-',
                    };
                @endphp
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 text-sm">
                    <div>
                        <p class="text-slate-500">رقم العملية</p>
                        <p class="font-semibold text-slate-900">{{ $shipment->operationno }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">اسم الشحنة</p>
                        <p class="font-semibold text-slate-900">{{ $shipment->shippmintno ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">رقم البيان الجمركي</p>
                        <p class="font-semibold text-slate-900">{{ $shipment->datano ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">حالة البيان</p>
                        <p class="font-semibold text-slate-900">{{ $customsStateLabel }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">مجموعة الشحن</p>
                        <p class="font-semibold text-slate-900">{{ $shipment->shipgroup?->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">المنفذ</p>
                        <p class="font-semibold text-slate-900">{{ $shipment->customsPort?->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">الشركة</p>
                        <p class="font-semibold text-slate-900">{{ $shipment->company?->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">القسم</p>
                        <p class="font-semibold text-slate-900">{{ $shipment->department?->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">الخط الملاحي</p>
                        <p class="font-semibold text-slate-900">{{ $shipment->shippingLine?->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">تاريخ الوصول</p>
                        <p class="font-semibold text-slate-900">{{ $shipment->dategase?->format('Y-m-d') ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">نوع الشحنة</p>
                        <p class="font-semibold text-slate-900">
                            {{ $shipment->shipmentType?->name ?? $shipment->shipmtype }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">حالة الشحنة</p>
                        <p class="font-semibold text-slate-900">
                            {{ $shipment->shipmentStatus?->name ?? $shipment->state }}</p>
                    </div>
                </div>
            </section>

            @php
                $firstContainer = $shipment->containers->first();
            @endphp
            <section class="space-y-4 border border-slate-100 rounded-xl p-6 bg-white shadow-sm">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900">بيانات الحاوية</h3>
                    <span class="text-xs text-slate-400">Step 2</span>
                </div>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 text-sm">
                    <div>
                        <p class="text-slate-500">رقم البوليصة</p>
                        <p class="font-semibold text-slate-900">{{ $shipment->pillno }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">رقم الحاوية</p>
                        <p class="font-semibold text-slate-900">{{ $shipment->pilno }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">رقم الفاتورة</p>
                        <p class="font-semibold text-slate-900">{{ $firstContainer?->invoice_number ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">رقم الباكنج ليست</p>
                        <p class="font-semibold text-slate-900">{{ $firstContainer?->packing_list_number ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">رقم شهادة المنشأ</p>
                        <p class="font-semibold text-slate-900">{{ $firstContainer?->certificate_of_origin ?? '-' }}</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-right border border-slate-200">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="px-3 py-2 border border-slate-200">عدد الحاويات</th>
                                <th class="px-3 py-2 border border-slate-200">حجم الحاويات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($shipment->containers as $container)
                                <tr class="border-b">
                                    <td class="px-3 py-2 border border-slate-200">{{ $container->container_count ?? '-' }}
                                    </td>
                                    <td class="px-3 py-2 border border-slate-200">{{ $container->container_size ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-3 py-4 text-center text-slate-500">لا توجد بيانات حاويات.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="space-y-4 border border-slate-100 rounded-xl p-6 bg-white shadow-sm">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900">بيانات المكتب</h3>
                    <span class="text-xs text-slate-400">Step 3</span>
                </div>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 text-sm">
                    <div>
                        <p class="text-slate-500">تاريخ الإرسال</p>
                        <p class="font-semibold text-slate-900">{{ $shipment->sendingdate?->format('Y-m-d') ?? '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-slate-500">تاريخ المكتب</p>
                        <p class="font-semibold text-slate-900">{{ $shipment->officedate?->format('Y-m-d') ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">تاريخ الموظف الميداني</p>
                        <p class="font-semibold text-slate-900">{{ $shipment->workerdate?->format('Y-m-d') ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">اسم الموظف الميداني</p>
                        <p class="font-semibold text-slate-900">{{ $shipment->workername ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">جهة التسليم</p>
                        <p class="font-semibold text-slate-900">{{ $shipment->relayname ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">تاريخ التسليم</p>
                        <p class="font-semibold text-slate-900">{{ $shipment->relaydate?->format('Y-m-d') ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">تاريخ العودة</p>
                        <p class="font-semibold text-slate-900">{{ $shipment->returndate?->format('Y-m-d') ?? '-' }}</p>
                    </div>
                </div>
            </section>

            <section class="space-y-4 border border-slate-100 rounded-xl p-6 bg-white shadow-sm">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900">المستندات</h3>
                    <span class="text-xs text-slate-400">Step 4</span>
                </div>
                <div class="space-y-2 text-sm">
                    @forelse($shipment->documents as $document)
                        <a class="text-blue-600 hover:underline"
                            href="{{ route('shipments.documents.download', $document) }}" download>
                            {{ $document->original_name }}
                        </a>
                    @empty
                        <p class="text-slate-500">لا توجد مستندات مرفقة.</p>
                    @endforelse
                </div>
                <div>
                    <p class="text-slate-500 text-sm">ملاحظات</p>
                    <p class="font-semibold text-slate-900 text-sm">{{ $shipment->others ?? '-' }}</p>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>