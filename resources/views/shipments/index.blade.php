<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-slate-900 leading-tight">تتبع الشحنات</h2>
                <p class="text-sm text-slate-500">بحث ذكي وفلاتر متقدمة لمراقبة حالة كل شحنة.</p>
            </div>
            <div class="flex items-center gap-3">
                @can('export shipments')
                    <a href="{{ route('shipments.export', request()->query()) }}"
                        class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-md text-slate-700 hover:bg-slate-50">
                        تصدير Excel
                    </a>
                @endcan
                @can('manage shipments')
                    <a href="{{ route('shipments.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        شحنة جديدة
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-12xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <form method="GET" action="{{ route('shipments.index') }}"
                class="bg-white shadow-sm sm:rounded-xl p-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4 border border-slate-100">
                @php
                    $customsStateValue = request('customs_state', request('dectype'));
                @endphp
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-700" for="search">بحث ذكي</label>
                    <div class="mt-2 relative">
                        <span class="absolute inset-y-0 right-3 flex items-center text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-4.35-4.35m1.6-4.15a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input id="search" name="search" type="text" value="{{ request('search') }}"
                            class="w-full rounded-md border-slate-300 pr-10 focus:border-blue-500 focus:ring-blue-500"
                            placeholder="رقم العملية، اسم الشحنة، البوليصة، الحاوية">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700" for="customs_port_id">المنفذ</label>
                    <select id="customs_port_id" name="customs_port_id" class="mt-2 w-full rounded-md border-slate-300">
                        <option value="">الكل</option>
                        @foreach($ports as $port)
                            <option value="{{ $port->id }}" @selected(request('customs_port_id') == $port->id)>
                                {{ $port->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700" for="company_id">الشركة</label>
                    <select id="company_id" name="company_id" class="mt-2 w-full rounded-md border-slate-300">
                        <option value="">الكل</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" @selected(request('company_id') == $company->id)>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700" for="department_id">القسم</label>
                    <select id="department_id" name="department_id" class="mt-2 w-full rounded-md border-slate-300">
                        <option value="">الكل</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" @selected(request('department_id') == $department->id)>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700" for="shippingno">الخط الملاحي</label>
                    <select id="shippingno" name="shippingno" class="mt-2 w-full rounded-md border-slate-300">
                        <option value="">الكل</option>
                        @foreach($shippingLines as $line)
                            <option value="{{ $line->id }}" @selected(request('shippingno') == $line->id)>{{ $line->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700" for="customs_state">حالة البيان</label>
                    <select id="customs_state" name="customs_state" class="mt-2 w-full rounded-md border-slate-300">
                        <option value="">الكل</option>
                        <option value="1" @selected((string) $customsStateValue === '1' || $customsStateValue === 'ضمان')>
                            ضمان</option>
                        <option value="2" @selected((string) $customsStateValue === '2' || $customsStateValue === 'سداد')>
                            سداد</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700" for="dategase_from">تاريخ الوصول من</label>
                    <input id="dategase_from" name="dategase_from" type="date" value="{{ request('dategase_from') }}"
                        class="mt-2 w-full rounded-md border-slate-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700" for="dategase_to">تاريخ الوصول إلى</label>
                    <input id="dategase_to" name="dategase_to" type="date" value="{{ request('dategase_to') }}"
                        class="mt-2 w-full rounded-md border-slate-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700" for="stillday_status">حالة السماح</label>
                    <select id="stillday_status" name="stillday_status" class="mt-2 w-full rounded-md border-slate-300">
                        <option value="">الكل</option>
                        <option value="near" @selected(request('stillday_status') === 'near')>قرب انتهاء</option>
                        <option value="expired" @selected(request('stillday_status') === 'expired')>منتهية</option>
                        <option value="available" @selected(request('stillday_status') === 'available')>متاحة</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">بحث</button>
                    <a href="{{ route('shipments.index') }}" class="px-4 py-2 border rounded-md text-slate-700">مسح</a>
                </div>
            </form>

            <div class="bg-white shadow-sm sm:rounded-xl p-6 overflow-x-auto border border-slate-100">
                <table class="min-w-full text-sm text-right">
                    <thead>
                        <tr class="text-slate-500 border-b">
                            <th class="py-2">العملية</th>
                            <th class="py-2">اسم الشحنة</th>
                            <th class="py-2">البوليصة</th>
                            <th class="py-2">عدد الحاويات</th>
                            <th class="py-2">البيان الجمركي</th>
                            <th class="py-2">حالة البيان</th>
                            <th class="py-2">المنفذ</th>
                            <th class="py-2">الشركة</th>
                            <th class="py-2">القسم</th>
                            <th class="py-2">الخط الملاحي</th>
                            <th class="py-2">الوصول</th>
                            <th class="py-2">نهاية السماح</th>
                            <th class="py-2">مرحلة الشحنة</th>
                            <th class="py-2">الأيام المتبقية</th>
                            <th class="py-2">المستندات</th>
                            <th class="py-2">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shipments as $shipment)
                            @php
                                $customsStateLabel = match ($shipment->customsData?->state) {
                                    1 => 'ضمان',
                                    2 => 'سداد',
                                    default => '-',
                                };

                                $stageLabel = $shipment->currentStage?->name ?? 'غير محددة';

                                $today = \Carbon\Carbon::today();
                                $computedEndAllowDate = $shipment->endallowdate;
                                $computedStillday = 0;
                                $allowanceDays = $shipment->shippingLine?->time;
                                if ($shipment->dategase && $allowanceDays) {
                                    $computedEndAllowDate = \Carbon\Carbon::parse($shipment->dategase)->addDays((int) $allowanceDays);
                                    $computedStillday = $today->diffInDays($computedEndAllowDate, false);
                                } elseif ($computedEndAllowDate) {
                                    $computedStillday = $today->diffInDays($computedEndAllowDate, false);
                                }

                                $containerCounts = $shipment->containers
                                    ->filter(fn($container) => $container->container_size && $container->container_count)
                                    ->groupBy('container_size')
                                    ->map(fn($group) => $group->sum('container_count'));

                                if ($containerCounts->isEmpty()) {
                                    $containerCounts = collect();
                                    if (($shipment->park40 ?? 0) > 0) {
                                        $containerCounts->put('40', $shipment->park40);
                                    }
                                    if (($shipment->park20 ?? 0) > 0) {
                                        $containerCounts->put('20', $shipment->park20);
                                    }
                                }

                                $preferredOrder = ['40', '40HC', '20'];
                                $orderedCounts = collect();
                                foreach ($preferredOrder as $size) {
                                    if ($containerCounts->has($size)) {
                                        $orderedCounts->put($size, $containerCounts->get($size));
                                    }
                                }
                                $orderedCounts = $orderedCounts->union($containerCounts->diffKeys($orderedCounts));
                                $containerSummary = $orderedCounts->map(fn($count, $size) => "حاويات {$size} قدم عدد {$count}")->implode('<br>');
                            @endphp
                            <tr class="border-b last:border-0">
                                <td class="py-2">{{ $shipment->operationno }}</td>
                                <td class="py-2">{{ $shipment->shippmintno ?? '-' }}</td>
                                <td class="py-2">{{ $shipment->pillno }}</td>
                                <td class="py-2">{!! $containerSummary !== '' ? $containerSummary : '-' !!}</td>
                                <td class="py-2">{{ $shipment->datano ?? '-' }}</td>
                                <td class="py-2">{{ $customsStateLabel }}</td>
                                <td class="py-2">{{ $shipment->customsPort?->name ?? '-' }}</td>
                                <td class="py-2">{{ $shipment->company?->name ?? '-' }}</td>
                                <td class="py-2">{{ $shipment->department?->name ?? '-' }}</td>
                                <td class="py-2">{{ $shipment->shippingLine?->name ?? '-' }}</td>
                                <td class="py-2">{{ $shipment->dategase?->format('Y-m-d') ?? '-' }}</td>
                                <td class="py-2">{{ $computedEndAllowDate?->format('Y-m-d') ?? '-' }}</td>
                                <td class="py-2">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                        {{ $stageLabel }}
                                    </span>
                                </td>
                                <td class="py-2">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded text-xs {{ $computedStillday < 0 ? 'bg-red-100 text-red-700' : ($computedStillday <= 3 ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                                        {{ $computedStillday }}
                                    </span>
                                </td>
                                <td class="py-2">
                                    @forelse($shipment->documents as $document)
                                        <a class="text-blue-600 hover:underline"
                                            href="{{ route('shipments.documents.download', $document) }}" download>
                                            {{ $document->original_name }}
                                        </a>
                                        @if(!$loop->last)
                                            <span class="text-gray-400">|</span>
                                        @endif
                                    @empty
                                        -
                                    @endforelse
                                </td>
                                <td class="py-2">
                                    <div class="flex items-center gap-2">
                                        @can('view shipments')
                                            <a href="{{ route('shipments.show', $shipment) }}"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-slate-100 text-slate-600 hover:bg-slate-200"
                                                title="استعراض" aria-label="استعراض">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                <span class="sr-only">استعراض</span>
                                            </a>
                                        @endcan
                                        @can('manage shipments')
                                            <a href="{{ route('shipments.edit', $shipment) }}"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-blue-50 text-blue-600 hover:bg-blue-100"
                                                title="تعديل" aria-label="تعديل">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                <span class="sr-only">تعديل</span>
                                            </a>
                                            <form method="POST" action="{{ route('shipments.destroy', $shipment) }}"
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
                                <td colspan="15" class="py-4 text-center text-gray-500">لا توجد شحنات بعد.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $shipments->links() }}
            </div>
        </div>
    </div>
    </div>
</x-app-layout>