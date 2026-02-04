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
            <h2 class="font-semibold text-xl text-slate-900 leading-tight">
                {{ $pageTitle }}
            </h2>
            <p class="text-sm text-slate-500 mt-1">{{ $pageSubtitle }}</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('status'))
                <div class="bg-emerald-50 text-emerald-800 px-4 py-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-amber-50 text-amber-800 px-4 py-3 rounded-lg">
                    <p class="font-semibold mb-2">يرجى تصحيح الأخطاء التالية:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data"
                class="bg-white shadow-sm sm:rounded-xl p-6 space-y-8 border border-slate-100">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between flex-wrap">
                    <div class="flex items-center gap-3">
                        <span
                            class="h-10 w-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold">1</span>
                        <div>
                            <p class="text-sm text-slate-500">المرحلة الأولى</p>
                            <p class="text-base font-semibold text-slate-900">البيانات الأساسية</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span
                            class="h-10 w-10 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center font-semibold">2</span>
                        <div>
                            <p class="text-sm text-slate-500">المرحلة الثانية</p>
                            <p class="text-base font-semibold text-slate-700">بيانات الحاويات</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span
                            class="h-10 w-10 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center font-semibold">3</span>
                        <div>
                            <p class="text-sm text-slate-500">المرحلة الثالثة</p>
                            <p class="text-base font-semibold text-slate-700">بيانات المكتب</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span
                            class="h-10 w-10 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center font-semibold">4</span>
                        <div>
                            <p class="text-sm text-slate-500">المرحلة الرابعة</p>
                            <p class="text-base font-semibold text-slate-700">المستندات</p>
                        </div>
                    </div>
                </div>

                <section class="space-y-4 border border-slate-100 rounded-xl p-5 bg-slate-50/30">
                    <div class="flex items-center justify-between">
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
                        <div x-data="{
                                open: false,
                                search: '',
                                selected: '{{ old('shippmintno', $shipment?->shippmintno ?? '') }}',
                                selectedName: '',
                                items: {{ Js::from($shipmentsList) }},
                                get filteredItems() {
                                    if (this.search === '') return this.items;
                                    const s = this.search.toLowerCase();
                                    return this.items.filter(item => item.name.toLowerCase().includes(s));
                                },
                                init() {
                                    if (this.selected) {
                                        const item = this.items.find(i => i.id == this.selected);
                                        if (item) this.selectedName = item.name;
                                    }
                                }
                            }" class="relative">
                            <label class="block text-sm font-medium text-slate-700" for="shippmintno">
                                اسم الشحنة </label>

                            <!-- Search Input -->
                            <input type="text" id="shippmintno_search" x-model="search" @focus="open = true"
                                @click.away="open = false" @keydown.escape="open = false"
                                class="mt-2 w-full rounded-md border-slate-300" placeholder="ابحث عن الشحنة..."
                                autocomplete="off" :value="selectedName">

                            <!-- Hidden Input for Form Submission -->
                            <input type="hidden" name="shippmintno" x-model="selected" required>

                            <!-- Dropdown -->
                            <div x-show="open && filteredItems.length > 0"
                                class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-md shadow-lg max-h-60 overflow-y-auto"
                                style="display: none;">
                                <template x-for="item in filteredItems" :key="item.id">
                                    <div @click="selected = item.id; selectedName = item.name; search = ''; open = false"
                                        class="px-4 py-2 cursor-pointer hover:bg-slate-50 text-sm text-slate-700 flex justify-between items-center">
                                        <span x-text="item.name"></span>
                                        <span class="text-xs text-slate-400" x-text="'الكمية: ' + item.quantity"></span>
                                    </div>
                                </template>
                            </div>
                            <div x-show="open && filteredItems.length === 0"
                                class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-md shadow-lg px-4 py-2 text-sm text-gray-500"
                                style="display: none;">
                                لا توجد شحنات مطابقة
                            </div>
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
                                class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-md shadow-lg max-h-60 overflow-y-auto"
                                style="display: none;">
                                <template x-for="item in filteredItems" :key="item">
                                    <div @click="selected = item; search = item; open = false"
                                        class="px-4 py-2 cursor-pointer hover:bg-slate-50 text-sm text-slate-700"
                                        x-text="item"></div>
                                </template>
                            </div>
                            <div x-show="open && filteredItems.length === 0"
                                class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-md shadow-lg px-4 py-2 text-sm text-gray-500"
                                style="display: none;">
                                لا توجد نتائج
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="shipgroupno">مجموعة
                                الشحن</label>
                            <select id="shipgroupno" name="shipgroupno" required
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
                            <label class="block text-sm font-medium text-slate-700" for="sectionno">القسم</label>
                            <select id="sectionno" name="sectionno" required
                                class="mt-2 w-full rounded-md border-slate-300">
                                <option value="">اختر القسم</option>
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
                            <label class="block text-sm font-medium text-slate-700" for="dategase">تاريخ الوصول</label>
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
                <section class="space-y-4 border border-slate-100 rounded-xl p-5 bg-slate-50/30" x-data="{
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
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900">بيانات الحاوية</h3>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-slate-400">Step 2</span>
                            <button type="button" @click="addSize()"
                                class="inline-flex items-center px-3 py-1.5 bg-emerald-600 text-white text-xs rounded-md hover:bg-emerald-700 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                            <label class="block text-sm font-medium text-slate-700" for="pilno">رقم الحاوية</label>
                            <input id="pilno" name="pilno" type="text" value="{{ old('pilno', $shipment?->pilno ?? '') }}"
                                required class="mt-2 w-full rounded-md border-slate-300" placeholder="رقم الحاوية">
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
                            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 border border-slate-200 rounded-lg p-4 bg-white relative">
                                <button type="button" @click="removeSize(index)" x-show="sizeRows.length > 1"
                                    class="absolute top-2 left-2 text-red-500 hover:text-red-700 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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

                <section class="space-y-4 border border-slate-100 rounded-xl p-5 bg-slate-50/30">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900">بيانات المكتب</h3>
                        <span class="text-xs text-slate-400">Step 3</span>
                    </div>
                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="sendingdate">تاريخ الإرسال</label>
                            <input id="sendingdate" name="sendingdate" type="date" value="{{ old('sendingdate', $shipment?->sendingdate?->format('Y-m-d')) }}"
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="officedate">تاريخ المكتب</label>
                            <input id="officedate" name="officedate" type="date" value="{{ old('officedate', $shipment?->officedate?->format('Y-m-d')) }}"
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="workerdate">تاريخ الموظف الميداني</label>
                            <input id="workerdate" name="workerdate" type="date" value="{{ old('workerdate', $shipment?->workerdate?->format('Y-m-d')) }}"
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="workername">اسم الموظف الميداني</label>
                            <input id="workername" name="workername" type="text" value="{{ old('workername', $shipment?->workername ?? '') }}"
                                class="mt-2 w-full rounded-md border-slate-300" placeholder="اسم الموظف">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="relayname">جهة التسليم</label>
                            <input id="relayname" name="relayname" type="text" value="{{ old('relayname', $shipment?->relayname ?? '') }}"
                                class="mt-2 w-full rounded-md border-slate-300" placeholder="جهة التسليم">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="relaydate">تاريخ التسليم</label>
                            <input id="relaydate" name="relaydate" type="date" value="{{ old('relaydate', $shipment?->relaydate?->format('Y-m-d')) }}"
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="returndate">تاريخ العودة</label>
                            <input id="returndate" name="returndate" type="date" value="{{ old('returndate', $shipment?->returndate?->format('Y-m-d')) }}"
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                    </div>
                </section>

                <section class="space-y-4 border border-slate-100 rounded-xl p-5 bg-slate-50/30">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900">المستندات المرفقة</h3>
                        <span class="text-xs text-slate-400">Step 4</span>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-3">اختر المستندات المرفقة</label>
                            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach($activeDocuments as $document)
                                    <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-lg hover:bg-slate-50 cursor-pointer transition">
                                        <input type="checkbox" name="attached_documents[]" value="{{ $document->id }}"
                                            {{ in_array($document->id, old('attached_documents', [])) ? 'checked' : '' }}
                                            class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                        <span class="text-sm text-slate-700">{{ $document->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @if($activeDocuments->isEmpty())
                                <p class="text-sm text-slate-500">لا توجد مستندات مفعلة. يمكنك إضافتها من <a href="{{ route('admin.documents.index') }}" class="text-blue-600 hover:underline">الإعدادات</a>.</p>
                            @endif
                        </div>

                        <div class="border-t border-slate-200 pt-4">
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
                        class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        {{ $submitLabel }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
