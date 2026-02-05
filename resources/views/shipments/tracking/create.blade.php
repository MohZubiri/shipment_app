<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">إضافة سجل تتبع</h2>
                <p class="text-sm text-slate-500">رقم العملية: {{ $shipment->operationno }}</p>
            </div>
            <a href="{{ route('shipments.tracking.index', $shipment) }}" class="text-sm text-blue-600 hover:text-blue-700">
                إلغاء
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="p-6 bg-white border shadow-sm sm:rounded-xl border-slate-100">
                <form method="POST" action="{{ route('shipments.tracking.store', $shipment) }}">
                    @csrf

                    <div class="space-y-6">
                        <!-- Stage Selection -->
                        <div>
                            <x-input-label for="stage_id" value="المرحلة" />
                            <select id="stage_id" name="stage_id" class="block mt-1 w-full rounded-lg shadow-sm border-slate-300 focus:border-blue-500 focus:ring-blue-500" required>
                                <option value="">اختر المرحلة</option>
                                @foreach($stages as $stage)
                                    <option value="{{ $stage->id }}"
                                        data-needs-containers="{{ $stage->needs_containers ? '1' : '0' }}"
                                        {{ $shipment->current_stage_id == $stage->id ? 'selected' : '' }}>
                                        {{ $stage->order }}. {{ $stage->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('stage_id')" class="mt-2" />
                        </div>

                        <!-- Event Date -->
                        <div>
                            <x-input-label for="event_date" value="تاريخ الحدث" />
                            <input type="datetime-local" id="event_date" name="event_date"
                                value="{{ old('event_date', now()->format('Y-m-d\\TH:i')) }}"
                                class="block mt-1 w-full rounded-lg shadow-sm border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                            <x-input-error :messages="$errors->get('event_date')" class="mt-2" />
                        </input>

                        <!-- Location -->
                        <div>
                            <x-input-label for="location" value="الموقع" />
                            <input type="text" id="location" name="location" class="block mt-1 w-full rounded-lg shadow-sm border-slate-300 focus:border-blue-500 focus:ring-blue-500" placeholder="مثال: ميناء جدة، المملكة العربية السعودية">
                            <x-input-error :messages="$errors->get('location')" class="mt-2" />
                        </div>

                        <div id="container-fields" class="hidden space-y-4">
                            <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                                هذه المرحلة تتطلب إدخال بيانات الحاويات (عدد وأرقام الحاويات).
                                <br>
                                <strong>إجمالي الحاويات:</strong> {{ $shipment->getTotalContainers() }} |
                                <strong>المستخدمة:</strong> {{ $shipment->getUsedContainers() }} |
                                <strong>المتبقي:</strong> {{ $shipment->getRemainingContainers() }}
                            </div>
                            <div>
                                <x-input-label for="container_count" value="عدد الحاويات" />
                                <input type="number" id="container_count" name="container_count" min="1" max="{{ $shipment->getRemainingContainers() }}" class="block mt-1 w-full rounded-lg shadow-sm border-slate-300 focus:border-blue-500 focus:ring-blue-500" value="{{ old('container_count') }}">
                                <p class="mt-1 text-xs text-slate-500">الحد الأقصى: {{ $shipment->getRemainingContainers() }} حاوية</p>
                                <x-input-error :messages="$errors->get('container_count')" class="mt-2" />
                            </div>
                            <div id="container-numbers-wrapper" class="space-y-2">
                                <x-input-label for="container_numbers" value="أرقام الحاويات" />
                                <div id="container-numbers-fields" class="grid grid-cols-2 gap-2">
                                    <!-- سيتم إنشاء حقول ديناميكية هنا -->
                                </div>
                                <x-input-error :messages="$errors->get('container_numbers')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <x-input-label for="notes" value="ملاحظات" />
                            <textarea id="notes" name="notes" rows="3" class="block mt-1 w-full rounded-lg shadow-sm border-slate-300 focus:border-blue-500 focus:ring-blue-500" placeholder="أي ملاحظات إضافية..."></textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <!-- Submit -->
                        <div class="flex gap-4 justify-end items-center">
                            <a href="{{ route('shipments.tracking.index', $shipment) }}" class="px-4 py-2 text-slate-700 hover:text-slate-900">
                                إلغاء
                            </a>
                            <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                حفظ سجل التتبع
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const stageSelect = document.getElementById('stage_id');
            const containerFields = document.getElementById('container-fields');
            const countInput = document.getElementById('container_count');
            const numbersWrapper = document.getElementById('container-numbers-wrapper');
            const numbersFields = document.getElementById('container-numbers-fields');

            const createContainerFields = (count) => {
                const currentInputs = numbersFields.querySelectorAll('input');
                const currentCount = currentInputs.length;

                if (count > currentCount) {
                    // إضافة حقول جديدة
                    for (let i = currentCount; i < count; i++) {
                        const div = document.createElement('div');
                        div.innerHTML = `
                            <input type="text" name="container_numbers[]"
                                class="block w-full rounded-lg shadow-sm border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                                placeholder="رقم الحاوية ${i + 1}" required>
                        `;
                        numbersFields.appendChild(div);
                    }
                } else if (count < currentCount) {
                    // إزالة الحقول الزائدة
                    for (let i = currentCount - 1; i >= count; i--) {
                        numbersFields.removeChild(numbersFields.lastElementChild);
                    }
                }
            };

            const toggleContainerFields = () => {
                const option = stageSelect.selectedOptions[0];
                const needs = option?.dataset.needsContainers === '1';
                containerFields.classList.toggle('hidden', !needs);
                countInput.required = needs;
                if (needs) {
                    createContainerFields(parseInt(countInput.value) || 0);
                } else {
                    numbersFields.innerHTML = '';
                }
            };

            stageSelect.addEventListener('change', toggleContainerFields);
            countInput.addEventListener('input', (e) => {
                const count = parseInt(e.target.value) || 0;
                const maxCount = parseInt(e.target.max) || 0;
                if (count > maxCount) {
                    e.target.value = maxCount;
                    createContainerFields(maxCount);
                } else {
                    createContainerFields(count);
                }
            });
            toggleContainerFields();
        });
    </script>
</x-app-layout>
