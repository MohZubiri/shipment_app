<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-slate-900 leading-tight">
                إدخال شحنة جديدة
            </h2>
            <p class="text-sm text-slate-500 mt-1">نموذج منظم بخطوات واضحة لسهولة الإدخال.</p>
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

            <form method="POST" action="{{ route('shipments.store') }}" enctype="multipart/form-data"
                class="bg-white shadow-sm sm:rounded-xl p-6 space-y-8 border border-slate-100">
                @csrf

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
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
                            <input id="operationno" name="operationno" type="number" value="{{ old('operationno') }}"
                                required class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="shippmintno">رقم
                                البيان/الصنف</label>
                            <input id="shippmintno" name="shippmintno" type="number" value="{{ old('shippmintno') }}"
                                required class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="datano">رقم البيان
                                الجمركي</label>
                            <input id="datano" name="datano" type="number" value="{{ old('datano') }}"
                                class="mt-2 w-full rounded-md border-slate-300" placeholder="اختياري">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="shipgroupno">مجموعة
                                الشحن</label>
                            <select id="shipgroupno" name="shipgroupno" required
                                class="mt-2 w-full rounded-md border-slate-300">
                                <option value="">اختر المجموعة</option>
                                @foreach($shipgroups as $shipgroup)
                                    <option value="{{ $shipgroup->id }}" @selected(old('shipgroupno') == $shipgroup->id)>
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
                                    <option value="{{ $port->id }}" @selected(old('customs_port_id') == $port->id)>
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
                                    <option value="{{ $department->id }}" @selected(old('departmentno') == $department->id)>
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
                                    <option value="{{ $section->id }}" @selected(old('sectionno') == $section->id)>
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
                                    <option value="{{ $line->id }}" @selected(old('shippingno') == $line->id)>
                                        {{ $line->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="dategase">تاريخ الوصول</label>
                            <input id="dategase" name="dategase" type="date" value="{{ old('dategase') }}"
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="shipmtype">نوع الشحنة</label>
                            <input id="shipmtype" name="shipmtype" type="number" value="{{ old('shipmtype', 0) }}"
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="state">حالة الشحنة</label>
                            <input id="state" name="state" type="number" value="{{ old('state', 0) }}"
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                    </div>
                </section>

                <section class="space-y-4 border border-slate-100 rounded-xl p-5 bg-slate-50/30">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900">بيانات الحاويات</h3>
                        <span class="text-xs text-slate-400">Step 2</span>
                    </div>
                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="pillno">رقم بوليصة
                                الشحن</label>
                            <input id="pillno" name="pillno" type="text" value="{{ old('pillno') }}" required
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="pilno">رقم الحاوية</label>
                            <input id="pilno" name="pilno" type="text" value="{{ old('pilno') }}" required
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="contatty">عدد الحاويات</label>
                            <input id="contatty" name="contatty" type="number" value="{{ old('contatty') }}"
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="value">القيمة</label>
                            <input id="value" name="value" type="number" value="{{ old('value') }}" step="1"
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="sendingdate">تاريخ
                                الإرسال</label>
                            <input id="sendingdate" name="sendingdate" type="date" value="{{ old('sendingdate') }}"
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="officedate">تاريخ
                                المكتب</label>
                            <input id="officedate" name="officedate" type="date" value="{{ old('officedate') }}"
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="workerdate">تاريخ الموظف
                                الميداني</label>
                            <input id="workerdate" name="workerdate" type="date" value="{{ old('workerdate') }}"
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="workername">اسم الموظف
                                الميداني</label>
                            <input id="workername" name="workername" type="text" value="{{ old('workername') }}"
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="relayname">جهة التسليم</label>
                            <input id="relayname" name="relayname" type="text" value="{{ old('relayname') }}"
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="relaydate">تاريخ
                                التسليم</label>
                            <input id="relaydate" name="relaydate" type="date" value="{{ old('relaydate') }}"
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="returndate">تاريخ
                                العودة</label>
                            <input id="returndate" name="returndate" type="date" value="{{ old('returndate') }}"
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                    </div>
                </section>

                <section class="space-y-4 border border-slate-100 rounded-xl p-5 bg-slate-50/30">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-900">المستندات المرفقة</h3>
                        <span class="text-xs text-slate-400">Step 3</span>
                    </div>
                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="bill_of_lading">مستندات بوليصة
                                الشحن</label>
                            <input id="bill_of_lading" name="bill_of_lading[]" type="file" multiple
                                class="mt-2 w-full text-sm text-slate-700">
                            <p class="mt-2 text-xs text-slate-500">PDF, JPG, PNG حتى 5MB للملف الواحد.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="others">ملاحظات</label>
                            <textarea id="others" name="others"
                                class="mt-2 w-full rounded-md border-slate-300">{{ old('others') }}</textarea>
                        </div>
                    </div>
                </section>

                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        حفظ الشحنة
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>