<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">تتبع الشحنة</h2>
                <p class="text-sm text-slate-500">رقم العملية: {{ $landShipping->operation_number }}</p>
            </div>
            <a href="{{ route('road-shipments.index') }}" class="text-sm text-blue-600 hover:text-blue-700">
                العودة للقائمة
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-12xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="bg-white shadow-sm sm:rounded-xl p-6 border border-slate-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-slate-900">سجل التتبع</h3>
                    <a href="{{ route('road-shipments.tracking.create', $landShipping) }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        إضافة سجل تتبع
                    </a>
                </div>

                @if($landShipping->trackingRecords->isEmpty())
                    <div class="text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <p class="text-slate-500">لا توجد سجلات تتبع لهذه الشحنة</p>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach($landShipping->trackingRecords as $index => $record)
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background-color: {{ $record->stage->color ?? '#6366f1' }}; color: white;">
                                        @if($record->stage->icon)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                        @else
                                            <span class="font-semibold">{{ $index + 1 }}</span>
                                        @endif
                                    </div>
                                    @if($loop->last)
                                        <div class="w-0.5 h-8 bg-slate-200"></div>
                                    @else
                                        <div class="w-0.5 flex-1 bg-slate-200"></div>
                                    @endif
                                </div>
                                <div class="flex-1 pb-6">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h4 class="font-semibold text-slate-900">{{ $record->stage->name }}</h4>
                                            @if($record->location)
                                                <p class="text-sm text-slate-500">{{ $record->location }}</p>
                                            @endif
                                            @if($record->container_count)
                                                <p class="text-sm text-slate-500">عدد الحاويات: {{ $record->container_count }}</p>
                                            @endif
                                            @if($record->container_numbers)
                                                <p class="text-sm text-slate-500">أرقام الحاويات: {{ $record->container_numbers }}</p>
                                            @endif
                                        </div>
                                        <div class="text-left">
                                            <p class="text-sm text-slate-500">{{ $record->event_date ? $record->event_date->format('Y-m-d H:i') : $record->created_at->format('Y-m-d H:i') }}</p>
                                            @if($record->createdBy)
                                                <p class="text-xs text-slate-400">{{ $record->createdBy->name }}</p>
                                            @endif
                                            <div class="mt-2 flex gap-2">
                                                <a href="{{ route('road-shipments.tracking.edit', [$landShipping, $record]) }}" class="text-xs text-blue-600 hover:text-blue-700">
                                                    تعديل
                                                </a>
                                                <form action="{{ route('road-shipments.tracking.destroy', [$landShipping, $record]) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا السجل؟')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs text-red-600 hover:text-red-700">
                                                        حذف
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @if($record->notes)
                                        <p class="mt-2 text-sm text-slate-600">{{ $record->notes }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            @if($landShipping->currentStage)
                <div class="bg-white shadow-sm sm:rounded-xl p-6 border border-slate-100">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">المرحلة الحالية</h3>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: {{ $landShipping->currentStage->color ?? '#6366f1' }}; color: white;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xl font-semibold text-slate-900">{{ $landShipping->currentStage->name }}</p>
                            <p class="text-sm text-slate-500">{{ $landShipping->currentStage->description }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-xl p-6 border border-slate-100">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">مراحل الشحن</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    @foreach($stages as $stage)
                        <div class="p-4 rounded-lg border {{ $landShipping->currentStage && $landShipping->currentStage->order >= $stage->order ? 'border-green-200 bg-green-50' : 'border-slate-200' }}">
                            <div class="flex flex-col items-center text-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center mb-2" style="background-color: {{ $stage->color }}; color: white;">
                                    @if($stage->icon)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    @endif
                                </div>
                                <p class="font-medium text-sm text-slate-900">{{ $stage->name }}</p>
                                @if($landShipping->currentStage && $landShipping->currentStage->order >= $stage->order)
                                    <span class="text-xs text-green-600 mt-1">✓ مكتمل</span>
                                @elseif($landShipping->currentStage && $landShipping->currentStage->order == $stage->order)
                                    <span class="text-xs text-blue-600 mt-1">● الحالي</span>
                                @else
                                    <span class="text-xs text-slate-400 mt-1">○ قادم</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
