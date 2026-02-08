<x-app-layout>
    @php
        $shipment = $shipment ?? null;
        $isEdit = $shipment !== null;
        $formAction = $isEdit ? route('shipments.update', $shipment) : route('shipments.store');
        $submitLabel = $isEdit ? 'حفظ التعديلات' : 'حفظ الشحنة';
        $pageTitle = $isEdit ? 'تعديل بيانات الشحنة' : 'إدخال شحنة جديدة';
        $pageSubtitle = $isEdit ? 'قم بتحديث البيانات المطلوبة ثم احفظ التعديلات.' : 'نموذج منظم بخطوات واضحة لسهولة الإدخال.';
    @endphp
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold leading-tight text-slate-900">
                {{ $pageTitle }}
            </h2>
            <p class="mt-1 text-sm text-slate-500">{{ $pageSubtitle }}</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            @if(session('status'))
                <div class="px-4 py-3 text-emerald-800 bg-emerald-50 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="px-4 py-3 text-amber-800 bg-amber-50 rounded-lg">
                    <p class="mb-2 font-semibold">يرجى تصحيح الأخطاء التالية:</p>
                    <ul class="space-y-1 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data"
                class="p-6 space-y-8 bg-white border shadow-sm sm:rounded-xl border-slate-100">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <div class="flex flex-col flex-wrap gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex gap-3 items-center">
                        <span
                            class="flex justify-center items-center w-10 h-10 font-semibold text-white bg-blue-600 rounded-full">1</span>
                        <div>
                            <p class="text-sm text-slate-500">المرحلة الأولى</p>
                            <p class="text-base font-semibold text-slate-900">البيانات الأساسية</p>
                        </div>
                    </div>
                    <div class="flex gap-3 items-center">
                        <span
                            class="flex justify-center items-center w-10 h-10 font-semibold rounded-full bg-slate-200 text-slate-600">2</span>
                        <div>
                            <p class="text-sm text-slate-500">المرحلة الثانية</p>
                            <p class="text-base font-semibold text-slate-700">بيانات الحاويات</p>
                        </div>
                    </div>
                    <div class="flex gap-3 items-center">
                        <span
                            class="flex justify-center items-center w-10 h-10 font-semibold rounded-full bg-slate-200 text-slate-600">3</span>
                        <div>
                            <p class="text-sm text-slate-500">المرحلة الثالثة</p>
                            <p class="text-base font-semibold text-slate-700">بيانات المكتب</p>
                        </div>
                    </div>
                    <div class="flex gap-3 items-center">
                        <span
                            class="flex justify-center items-center w-10 h-10 font-semibold rounded-full bg-slate-200 text-slate-600">4</span>
                        <div>
                            <p class="text-sm text-slate-500">المرحلة الرابعة</p>
                            <p class="text-base font-semibold text-slate-700">المستندات</p>
                        </div>
                    </div>
                </div>

                <section class="p-5 space-y-4 rounded-xl border border-slate-100 bg-slate-50/30">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-slate-900">البيانات الأساسية</h3>
                        <span class="text-xs text-slate-400">Step 1</span>
                    </div>
                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="operationno">رقم
                                العملية</label>
                            <input id="operationno" name="operationno" type="number" value="{{ old('operationno', $shipment?->operationno ?? '') }}"
                                required class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="shippmintno">
                                اسم الشحنة </label>
                            <input id="shippmintno" name="shippmintno" type="text"
                                value="{{ old('shippmintno', $shipment?->shippmintno ?? '') }}"
                                required class="mt-2 w-full rounded-md border-slate-300"
                                placeholder="أدخل اسم الشحنة">
                        </div>
                        <div x-data="{
                                open: false,
                                search: '{{ old('datano', $shipment?->datano ?? '') }}',
                                selected: '{{ old('datano', $shipment?->datano ?? '') }}',
                                items: {{ json_encode($customsDataList) }},
                                get filteredItems() {
                                    if (this.search === '') return this.items;
                                    return this.items.filter(item => item.toString().includes(this.search));
                                }
                            }" class="relative">
                            <label class="block text-sm font-medium text-slate-700" for="datano">رقم البيان
                                الجمركي</label>

                            <!-- Search Input -->
                            <input type="text" id="datano_search" x-model="search" @focus="open = true"
                                @click.away="open = false" @keydown.escape="open = false"
                                class="mt-2 w-full rounded-md border-slate-300" placeholder="ابحث عن رقم البيان..."
                                autocomplete="off">

                            <!-- Hidden Input for Form Submission -->
                            <input type="hidden" name="datano" x-model="selected">

                            <!-- Dropdown -->
                            <div x-show="open && filteredItems.length > 0"
                                class="overflow-y-auto absolute z-50 mt-1 w-full max-h-60 bg-white rounded-md border border-gray-200 shadow-lg"
                                style="display: none;">
                                <template x-for="item in filteredItems" :key="item">
                                    <div @click="selected = item; search = item; open = false"
                                        class="px-4 py-2 text-sm cursor-pointer hover:bg-slate-50 text-slate-700"
                                        x-text="item"></div>
                                </template>
                            </div>
                            <div x-show="open && filteredItems.length === 0"
                                class="absolute z-50 px-4 py-2 mt-1 w-full text-sm text-gray-500 bg-white rounded-md border border-gray-200 shadow-lg"
                                style="display: none;">
                                لا توجد نتائج
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="shipgroupno">مجموعة
                                الشحن</label>
                            <select id="shipgroupno" name="shipgroupno" 
                                class="mt-2 w-full rounded-md border-slate-300">
                                <option value="">اختر المجموعة</option>
                                @foreach($shipgroups as $shipgroup)
                                    <option value="{{ $shipgroup->id }}" @selected(old('shipgroupno', $shipment?->shipgroupno ?? '') == $shipgroup->id)>
                                        {{ $shipgroup->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="customs_port_id">المنفذ</label>
                            <select id="customs_port_id" name="customs_port_id" required
                                class="mt-2 w-full rounded-md border-slate-300">
                                <option value="">اختر المنفذ</option>
                                @foreach($customsPorts as $port)
                                    <option value="{{ $port->id }}" @selected(old('customs_port_id', $shipment?->customs_port_id ?? '') == $port->id)>
                                        {{ $port->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="departmentno">القسم</label>
                            <select id="departmentno" name="departmentno" required
                                class="mt-2 w-full rounded-md border-slate-300">
                                <option value="">اختر القسم</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" @selected(old('departmentno', $shipment?->departmentno ?? '') == $department->id)>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="sectionno">الشعبة</label>
                            <select id="sectionno" name="sectionno"
                                class="mt-2 w-full rounded-md border-slate-300">
                                <option value="">اختر الشعبة</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}" @selected(old('sectionno', $shipment?->sectionno ?? '') == $section->id)>
                                        {{ $section->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="shippingno">الخط
                                الملاحي</label>
                            <select id="shippingno" name="shippingno" class="mt-2 w-full rounded-md border-slate-300">
                                <option value="">اختر الخط الملاحي</option>
                                @foreach($shippingLines as $line)
                                    <option value="{{ $line->id }}" @selected(old('shippingno', $shipment?->shippingno ?? '') == $line->id)>
                                        {{ $line->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="dategase">تاريخ وصول الشحنة  </label>
                            <input id="dategase" name="dategase" type="date" value="{{ old('dategase', $shipment?->dategase?->format('Y-m-d')) }}"
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="shipmtype">نوع الشحنة</label>
                            <select id="shipmtype" name="shipmtype" required
                                class="mt-2 w-full rounded-md border-slate-300">
                                <option value="">اختر نوع الشحنة</option>
                                @foreach($shipmentTypes as $type)
                                    <option value="{{ $type->id }}" @selected(old('shipmtype', $shipment?->shipmtype ?? '') == $type->id)>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="state">حالة الشحنة</label>
                            <select id="state" name="state" required class="mt-2 w-full rounded-md border-slate-300">
                                <option value="">اختر حالة الشحنة</option>
                                @foreach($shipmentStatuses as $status)
                                    <option value="{{ $status->id }}" @selected(old('state', $shipment?->state ?? '') == $status->id)>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </section>

                @php
                    $existingContainers = $shipment?->containers ?? collect();
                    $firstContainer = $existingContainers->first();
                    $containerMeta = [
                        'invoice_number' => old('containers.0.invoice_number', $firstContainer?->invoice_number),
                        'packing_list_number' => old('containers.0.packing_list_number', $firstContainer?->packing_list_number),
                        'certificate_of_origin' => old('containers.0.certificate_of_origin', $firstContainer?->certificate_of_origin),
                    ];
                    $sizeRows = old('containers');
                    if (!is_array($sizeRows) || count($sizeRows) === 0) {
                        $sizeRows = $existingContainers->map(fn ($container) => [
                            'container_size' => $container->container_size,
                            'container_count' => $container->container_count,
                        ])->values()->all();
                    }
                    if (!is_array($sizeRows) || count($sizeRows) === 0) {
                        $sizeRows = [['container_size' => '', 'container_count' => 1]];
                    }
                @endphp
                <section class="p-5 space-y-4 rounded-xl border border-slate-100 bg-slate-50/30" x-data="{
                    containerMeta: {{ Js::from($containerMeta) }},
                    sizeRows: {{ Js::from($sizeRows) }},
                    init() {
                        this.sizeRows = this.sizeRows.map(row => ({
                            container_size: row.container_size ?? '',
                            container_count: row.container_count ?? 1,
                        }));
                    },
                    addSize() {
                        this.sizeRows.push({ container_size: '', container_count: 1 });
                    },
                    removeSize(index) {
                        if (this.sizeRows.length > 1) {
                            this.sizeRows.splice(index, 1);
                        }
                    }
                }">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-slate-900">بيانات الحاوية</h3>
                        <div class="flex gap-2 items-center">
                            <span class="text-xs text-slate-400">Step 2</span>
                            <button type="button" @click="addSize()"
                                class="inline-flex items-center px-3 py-1.5 text-xs text-white bg-emerald-600 rounded-md transition hover:bg-emerald-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                إضافة حجم
                            </button>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="pillno">رقم البوليصة</label>
                            <input id="pillno" name="pillno" type="text" value="{{ old('pillno', $shipment?->pillno ?? '') }}"
                                required class="mt-2 w-full rounded-md border-slate-300" placeholder="رقم البوليصة">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="container_invoice_number">رقم الفاتورة</label>
                            <input id="container_invoice_number" type="text" x-model="containerMeta.invoice_number"
                                class="mt-2 w-full rounded-md border-slate-300" placeholder="رقم الفاتورة">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="container_packing_list_number">رقم الباكنج ليست</label>
                            <input id="container_packing_list_number" type="text" x-model="containerMeta.packing_list_number"
                                class="mt-2 w-full rounded-md border-slate-300" placeholder="رقم الباكنج ليست">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="container_certificate_of_origin">رقم شهادة المنشأ</label>
                            <input id="container_certificate_of_origin" type="text" x-model="containerMeta.certificate_of_origin"
                                class="mt-2 w-full rounded-md border-slate-300" placeholder="رقم شهادة المنشأ">
                        </div>
                    </div>

                    <div class="space-y-3">
                        <template x-for="(row, index) in sizeRows" :key="index">
                            <div class="grid relative gap-4 p-4 bg-white rounded-lg border sm:grid-cols-2 lg:grid-cols-3 border-slate-200">
                                <button type="button" @click="removeSize(index)" x-show="sizeRows.length > 1"
                                    class="absolute top-2 left-2 text-red-500 transition hover:text-red-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>

                                <input type="hidden" :name="'containers[' + index + '][invoice_number]'" x-model="containerMeta.invoice_number">
                                <input type="hidden" :name="'containers[' + index + '][packing_list_number]'" x-model="containerMeta.packing_list_number">
                                <input type="hidden" :name="'containers[' + index + '][certificate_of_origin]'" x-model="containerMeta.certificate_of_origin">

                                <div>
                                    <label class="block text-sm font-medium text-slate-700">عدد الحاويات</label>
                                    <input type="number" :name="'containers[' + index + '][container_count]'" x-model.number="row.container_count"
                                        class="mt-2 w-full rounded-md border-slate-300" min="1" placeholder="1">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700">حجم الحاويات</label>
                                    <select :name="'containers[' + index + '][container_size]'" x-model="row.container_size"
                                        class="mt-2 w-full rounded-md border-slate-300">
                                        <option value="">اختر الحجم</option>
                                        <option value="20">20 قدم</option>
                                        <option value="40">40 قدم</option>
                                        <option value="40HC">40 قدم HC</option>
                                        <option value="45">45 قدم</option>
                                    </select>
                                </div>
                            </div>
                        </template>
                    </div>
                </section>

                <section class="p-5 space-y-4 rounded-xl border border-slate-100 bg-slate-50/30">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-slate-900">بيانات المكتب</h3>
                        <span class="text-xs text-slate-400">Step 3</span>
                    </div>
                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="sendingdate">تاريخ إرسل المستندات</label>
                            <input id="sendingdate" name="sendingdate" type="date" value="{{ old('sendingdate', $shipment?->sendingdate?->format('Y-m-d')) }}"
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="officedate">تاريخ  استلام المكتب المستندات</label>
                            <input id="officedate" name="officedate" type="date" value="{{ old('officedate', $shipment?->officedate?->format('Y-m-d')) }}"
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="workerdate">تاريخ  استلام المخلص</label>
                            <input id="workerdate" name="workerdate" type="date" value="{{ old('workerdate', $shipment?->workerdate?->format('Y-m-d')) }}"
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="workername">اسم المخلص </label>
                            <input id="workername" name="workername" type="text" value="{{ old('workername', $shipment?->workername ?? '') }}"
                                class="mt-2 w-full rounded-md border-slate-300" placeholder="اسم الموظف">
                        </div>
                          <div>
                            <label class="block text-sm font-medium text-slate-700" for="returndate">تاريخ تسليم التصفية </label>
                            <input id="returndate" name="returndate" type="date" value="{{ old('returndate', $shipment?->returndate?->format('Y-m-d')) }}"
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="relayname">الموظف المستلم للتصفية  </label>
                            <input id="relayname" name="relayname" type="text" value="{{ old('relayname', $shipment?->relayname ?? '') }}"
                                class="mt-2 w-full rounded-md border-slate-300" placeholder="اسم الموظف">
                        </div>
                     
                    </div>
                </section>

                <section class="p-5 space-y-4 rounded-xl border border-slate-100 bg-slate-50/30">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-slate-900">المستندات المرفقة</h3>
                        <span class="text-xs text-slate-400">Step 4</span>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block mb-3 text-sm font-medium text-slate-700">اختر المستندات المرفقة</label>
                            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach($activeDocuments as $document)
                                    <label class="flex gap-3 items-center p-3 rounded-lg border transition cursor-pointer border-slate-200 hover:bg-slate-50">
                                        <input type="checkbox" name="attached_documents[]" value="{{ $document->id }}"
                                            {{ in_array($document->id, old('attached_documents', [])) ? 'checked' : '' }}
                                            class="text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                                        <span class="text-sm text-slate-700">{{ $document->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @if($activeDocuments->isEmpty())
                                <p class="text-sm text-slate-500">لا توجد مستندات مفعلة. يمكنك إضافتها من <a href="{{ route('admin.documents.index') }}" class="text-blue-600 hover:underline">الإعدادات</a>.</p>
                            @endif
                        </div>

                        <div class="pt-4 border-t border-slate-200">
                            <label class="block text-sm font-medium text-slate-700" for="documents_zip">رفع ملف المستندات (ZIP)</label>
                            <input id="documents_zip" name="documents_zip" type="file" accept=".zip"
                                class="mt-2 w-full text-sm text-slate-700">
                            <p class="mt-2 text-xs text-slate-500">يرجى رفع جميع المستندات في ملف ZIP واحد (الحد الأقصى 50MB).</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="others">ملاحظات</label>
                            <textarea id="others" name="others"
                                class="mt-2 w-full rounded-md border-slate-300" rows="3">{{ old('others', $shipment?->others ?? '') }}</textarea>
                        </div>
                    </div>
                </section>

                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-6 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                        {{ $submitLabel }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
