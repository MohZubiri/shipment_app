<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">إضافة شحنة دولية برية</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('road-shipments.store') }}" enctype="multipart/form-data"
                class="bg-white shadow-sm sm:rounded-xl p-6 space-y-6 border border-slate-100">
                @csrf

                @if($errors->any())
                    <div class="bg-amber-50 text-amber-800 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="operation_number">رقم العملية</label>
                        <input id="operation_number" name="operation_number" type="text" value="{{ old('operation_number') }}"
                            required class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="locomotive_number">رقم القاطرة</label>
                        <input id="locomotive_number" name="locomotive_number" type="text" value="{{ old('locomotive_number') }}"
                            class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="company_id">الشركة</label>
                        <select id="company_id" name="company_id" required class="mt-2 w-full rounded-md border-slate-300">
                            <option value="">اختر الشركة</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" @selected(old('company_id') == $company->id)>{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="section_id">القسم</label>
                        <select id="section_id" name="section_id" class="mt-2 w-full rounded-md border-slate-300">
                            <option value="">اختياري</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" @selected(old('section_id') == $department->id)>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="shipment_name">اسم الشحنة</label>
                        <input id="shipment_name" name="shipment_name" type="text" value="{{ old('shipment_name') }}"
                            class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="declaration_number">رقم البيان</label>
                        <select id="declaration_number" name="declaration_number" class="mt-2 w-full rounded-md border-slate-300">
                            <option value="">اختياري</option>
                            @foreach($customsDataList as $datano)
                                <option value="{{ $datano }}" @selected((string) old('declaration_number') === (string) $datano)>{{ $datano }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="arrival_date">تاريخ الوصول</label>
                        <input id="arrival_date" name="arrival_date" type="date" value="{{ old('arrival_date') }}"
                            class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="exit_date">تاريخ الخروج</label>
                        <input id="exit_date" name="exit_date" type="date" value="{{ old('exit_date') }}"
                            class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="docking_days">أيام الربط</label>
                        <input id="docking_days" name="docking_days" type="number" min="0" value="{{ old('docking_days') }}"
                            class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="documents_sent_date">تاريخ إرسال المستندات</label>
                        <input id="documents_sent_date" name="documents_sent_date" type="date" value="{{ old('documents_sent_date') }}"
                            class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="documents_type">نوع المستندات</label>
                        <input id="documents_type" name="documents_type" type="text" value="{{ old('documents_type') }}"
                            class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="warehouse_arrival_date">تاريخ وصول المخزن</label>
                        <input id="warehouse_arrival_date" name="warehouse_arrival_date" type="date" value="{{ old('warehouse_arrival_date') }}"
                            class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                </div>

                <section class="p-5 space-y-4 rounded-xl border border-slate-100 bg-slate-50/30">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-slate-900">المستندات المرفقة</h3>
                        <span class="text-xs text-slate-400">Step 2</span>
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
                    </div>
                </section>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('road-shipments.index') }}" class="px-4 py-2 border rounded-md text-slate-700">إلغاء</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
