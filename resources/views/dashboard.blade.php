<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-slate-900 leading-tight">
                    لوحة التحكم
                </h2>
                <p class="text-sm text-slate-500">نظرة عامة على حركة الشحنات اليوم</p>
            </div>
            <span class="text-sm text-slate-500">تاريخ اليوم: {{ $today->format('Y-m-d') }}</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="bg-white shadow-sm sm:rounded-xl p-5 border border-slate-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-500">إجمالي الشحنات النشطة</p>
                            <p class="text-2xl font-semibold text-slate-900">{{ $totalActive }}</p>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white shadow-sm sm:rounded-xl p-5 border border-slate-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-500">شحنات بانتظار الإفراج</p>
                            <p class="text-2xl font-semibold text-slate-900">{{ $pendingRelease }}</p>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white shadow-sm sm:rounded-xl p-5 border border-slate-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-500">قرب انتهاء السماح</p>
                            <p class="text-2xl font-semibold text-slate-900">{{ $nearExpiryCount }}</p>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M6.938 19h10.124c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L5.206 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white shadow-sm sm:rounded-xl p-5 border border-slate-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-500">الشحنات المكتملة اليوم</p>
                            <p class="text-2xl font-semibold text-slate-900">{{ $completedToday }}</p>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="bg-white shadow-sm sm:rounded-xl p-6 border border-slate-100 lg:col-span-2">
                    <div class="flex items-center justify-between mb-6">
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
                                <div class="flex items-center justify-between text-sm text-slate-600">
                                    <span class="flex items-center gap-2">
                                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                                            </svg>
                                        </span>
                                        {{ $department->name }}
                                    </span>
                                    <span class="font-semibold text-slate-900">{{ $department->shipments_count }}</span>
                                </div>
                                <div class="mt-2 h-2 w-full rounded-full bg-slate-100">
                                    <div class="h-2 rounded-full bg-blue-600" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">لا توجد بيانات بعد.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-xl p-6 border border-slate-100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-slate-900">التنبيهات العاجلة</h3>
                        <span class="text-xs text-rose-500">أقل من 3 أيام</span>
                    </div>
                    <div class="space-y-4">
                        @forelse($alerts as $alert)
                            <div class="p-4 rounded-lg border border-rose-100 bg-rose-50">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="font-semibold text-slate-900">{{ $alert->shipment_name }}</span>
                                    <span class="text-rose-600 font-semibold">{{ $alert->still_days }} يوم</span>
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
        </div>
    </div>
</x-app-layout>
