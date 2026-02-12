<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-slate-900">
                    <span class="text-sm font-semibold text-slate-801">{{ $appSetting->system_name ?? config('app.name') }}</span>
                </h2>
                <p class="text-sm text-slate-500">نظرة عامة على حركة الشحنات اليوم</p>
            </div>
            <span class="text-sm text-slate-500">تاريخ اليوم: {{ $today->format('Y-m-d') }}</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto space-y-8 max-w-7xl sm:px-6 lg:px-8">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="p-5 bg-white border shadow-sm sm:rounded-xl border-slate-100">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-slate-500">إجمالي الشحنات النشطة</p>
                            <p class="text-2xl font-semibold text-slate-900">{{ $totalActive }}</p>
                        </div>
                        <div class="flex justify-center items-center w-10 h-10 text-blue-600 bg-blue-50 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="p-5 bg-white border shadow-sm sm:rounded-xl border-slate-100">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-slate-500">شحنات بانتظار الإفراج</p>
                            <p class="text-2xl font-semibold text-slate-900">{{ $pendingRelease }}</p>
                        </div>
                        <div class="flex justify-center items-center w-10 h-10 text-amber-600 bg-amber-50 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="p-5 bg-white border shadow-sm sm:rounded-xl border-slate-100">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-slate-500">قرب انتهاء السماح</p>
                            <p class="text-2xl font-semibold text-slate-900">{{ $nearExpiryCount }}</p>
                        </div>
                        <div class="flex justify-center items-center w-10 h-10 text-rose-600 bg-rose-50 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M6.938 19h10.124c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L5.206 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="p-5 bg-white border shadow-sm sm:rounded-xl border-slate-100">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-slate-500">الشحنات المكتملة اليوم</p>
                            <p class="text-2xl font-semibold text-slate-900">{{ $completedToday }}</p>
                        </div>
                        <div class="flex justify-center items-center w-10 h-10 text-emerald-600 bg-emerald-50 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-white border shadow-sm sm:rounded-xl border-slate-100">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">قوائم الشحنات</h3>
                        <p class="text-sm text-slate-500">تنقل سريع بين أنواع الشحن</p>
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <a href="{{ route('shipments.index') }}"
                        class="flex items-center justify-between p-4 rounded-lg border border-slate-200 hover:bg-slate-50">
                        <div>
                            <p class="text-sm text-slate-500">الشحن الدولي البحري</p>
                            <p class="text-lg font-semibold text-slate-900">{{ $seaShipmentsCount }}</p>
                        </div>
                        <span class="text-xs text-slate-400">قائمة</span>
                    </a>
                    <a href="{{ route('road-shipments.index') }}"
                        class="flex items-center justify-between p-4 rounded-lg border border-slate-200 hover:bg-slate-50">
                        <div>
                            <p class="text-sm text-slate-500">الشحن الدولي البري</p>
                            <p class="text-lg font-semibold text-slate-900">{{ $internationalRoadCount }}</p>
                        </div>
                        <span class="text-xs text-slate-400">قائمة</span>
                    </a>
                    <a href="{{ route('local-shipments.index') }}"
                        class="flex items-center justify-between p-4 rounded-lg border border-slate-200 hover:bg-slate-50">
                        <div>
                            <p class="text-sm text-slate-500">الشحن المحلي البري</p>
                            <p class="text-lg font-semibold text-slate-900">{{ $localRoadCount }}</p>
                        </div>
                        <span class="text-xs text-slate-400">قائمة</span>
                    </a>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="p-6 bg-white border shadow-sm sm:rounded-xl border-slate-100 lg:col-span-2">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">توزيع الشحنات حسب المنافذ</h3>
                            <p class="text-sm text-slate-500">أحدث البيانات المسجلة للوصول</p>
                        </div>
                        <span class="text-xs text-slate-400">Ports Overview</span>
                    </div>
                    <div class="space-y-4">
                        @forelse($byDepartment as $department)
                            @php
                                $percent = round(($department->shipments_count / $maxDepartmentCount) * 100);
                            @endphp
                            <div>
                                <div class="flex justify-between items-center text-sm text-slate-600">
                                    <span class="flex gap-2 items-center">
                                        <span class="inline-flex justify-center items-center w-8 h-8 text-blue-600 bg-blue-50 rounded-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                                            </svg>
                                        </span>
                                        {{ $department->name }}
                                    </span>
                                    <span class="font-semibold text-slate-900">{{ $department->shipments_count }}</span>
                                </div>
                                <div class="mt-2 w-full h-2 rounded-full bg-slate-100">
                                    <div class="h-2 bg-blue-600 rounded-full" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">لا توجد بيانات بعد.</p>
                        @endforelse
                    </div>
                </div>

                <div class="p-6 bg-white border shadow-sm sm:rounded-xl border-slate-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-slate-900">التنبيهات العاجلة</h3>
                        <span class="text-xs text-rose-500">أقل من 3 أيام</span>
                    </div>
                    <div class="space-y-4">
                        @forelse($alerts as $alert)
                            <div class="p-4 bg-rose-50 rounded-lg border border-rose-100">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="font-semibold text-slate-900">{{ $alert->shipment_name }}</span>
                                    <span class="font-semibold text-rose-600">{{ $alert->still_days }} يوم</span>
                                </div>
                                <div class="mt-2 text-xs text-slate-500">
                                    {{ $alert->shipment?->department?->name ?? '—' }} · نهاية السماح: {{ $alert->end_date?->format('Y-m-d') ?? '—' }}
                                </div>
                            </div>
                        @empty
                            <div class="text-sm text-slate-500">لا توجد تنبيهات حالياً.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="p-6 bg-white border shadow-sm sm:rounded-xl border-slate-100">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">توزيع الشحنات حسب المراحل</h3>
                        <p class="text-sm text-slate-500">حالة الشحنات في الرحلة الكاملة</p>
                    </div>
                    <span class="text-xs text-slate-400">Stages Overview</span>
                </div>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 lg:grid-cols-5">
                    @php
                        $stages = \App\Models\ShipmentStage::active()->ordered()->get();
                        foreach ($stages as $stage) {
                            $count = \App\Models\ShipmentTransaction::where('current_stage_id', $stage->id)->count();
                    @endphp
                    <div class="p-4 rounded-lg border transition-colors border-slate-200 hover:border-blue-300">
                        <div class="flex flex-col items-center text-center">
                            <div class="flex justify-center items-center mb-2 w-10 h-10 rounded-full" style="background-color: {{ $stage->color }}; color: white;">
                                @if($stage->icon)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                @endif
                            </div>
                            <p class="text-sm font-medium text-slate-900">{{ $stage->name }}</p>
                            <p class="text-2xl font-bold text-slate-900">{{ $count }}</p>
                        </div>
                    </div>
                    @php
                        }
                    @endphp
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
