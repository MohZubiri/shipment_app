<x-app-layout>
    @php
        $pageTitle = 'تعديل شحنة دولية برية';
        $pageSubtitle = 'قم بتحديث البيانات المطلوبة ثم احفظ التعديلات.';
    @endphp
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-slate-900">
                    {{ $pageTitle }}
                </h2>
                <p class="mt-1 text-sm text-slate-500">{{ $pageSubtitle }}</p>
            </div>
            <a href="{{ route('road-shipments.tracking.index', $landShipping) }}"
               class="px-4 py-2 text-sm font-medium text-white transition-colors duration-150 bg-indigo-600 border border-transparent rounded-lg active:bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:shadow-outline-indigo">
               تتبع الشحنة
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
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

            <form method="POST" action="{{ route('road-shipments.update', $landShipping) }}" enctype="multipart/form-data"
                class="p-6 space-y-8 bg-white border shadow-sm sm:rounded-xl border-slate-100">
                @csrf
                @method('PUT')

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
                            <label class="block text-sm font-medium text-slate-700" for="operation_number">رقم العملية</label>
                            <input id="operation_number" name="operation_number" type="text"
                                value="{{ old('operation_number', $landShipping->operation_number) }}" required
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>

                        <div class="sm:col-span-2 lg:col-span-3">
                            <label class="block text-sm font-medium text-slate-700">أرقام القواطر</label>
                            <div id="locomotives-container" class="space-y-2 mt-2">
                                @php
                                    $locomotives = old('locomotive_numbers') ?? $landShipping->locomotives->pluck('locomotive_number')->toArray();
                                @endphp

                                @if(count($locomotives) > 0)
                                    @foreach($locomotives as $number)
                                        <div class="flex gap-2">
                                            <input name="locomotive_numbers[]" type="text" value="{{ $number }}" class="w-full rounded-md border-slate-300" placeholder="رقم القاطرة">
                                            <button type="button" class="px-3 py-2 text-red-600 bg-red-50 rounded-md hover:bg-red-100" onclick="this.parentElement.remove()">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="flex gap-2">
                                        <input name="locomotive_numbers[]" type="text" class="w-full rounded-md border-slate-300" placeholder="رقم القاطرة">
                                        <button type="button" class="px-3 py-2 text-red-600 bg-red-50 rounded-md hover:bg-red-100" onclick="this.parentElement.remove()">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" onclick="addLocomotive()" class="mt-2 text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                إضافة قاطرة
                            </button>
                            <script>
                                function addLocomotive() {
                                    const container = document.getElementById('locomotives-container');
                                    const div = document.createElement('div');
                                    div.className = 'flex gap-2';
                                    div.innerHTML = `
                                        <input name="locomotive_numbers[]" type="text" class="w-full rounded-md border-slate-300" placeholder="رقم القاطرة">
                                        <button type="button" class="px-3 py-2 text-red-600 bg-red-50 rounded-md hover:bg-red-100" onclick="this.parentElement.remove()">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    `;
                                    container.appendChild(div);
                                }
                            </script>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="company_id">الشركة</label>
                            <select id="company_id" name="company_id" required class="mt-2 w-full rounded-md border-slate-300">
                                <option value="">اختر الشركة</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" @selected(old('company_id', $landShipping->company_id) == $company->id)>{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="department_id">القسم</label>
                            <select id="department_id" name="department_id" class="mt-2 w-full rounded-md border-slate-300">
                                <option value="">اختر القسم</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" @selected(old('department_id', $landShipping->department_id) == $department->id)>{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="customs_port_id">المنفذ</label>
                            <select id="customs_port_id" name="customs_port_id" class="mt-2 w-full rounded-md border-slate-300">
                                <option value="">اختر المنفذ</option>
                                @foreach($customsPorts as $port)
                                    <option value="{{ $port->id }}" @selected(old('customs_port_id', $landShipping->customs_port_id) == $port->id)>{{ $port->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="shipment_name">اسم الشحنة</label>
                            <input id="shipment_name" name="shipment_name" type="text"
                                value="{{ old('shipment_name', $landShipping->shipment_name) }}"
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="declaration_number">رقم البيان</label>
                            <select id="declaration_number" name="declaration_number" class="mt-2 w-full rounded-md border-slate-300">
                                <option value="">اختياري</option>
                                @foreach($customsDataList as $datano)
                                    <option value="{{ $datano }}" @selected((string) old('declaration_number', $landShipping->declaration_number) === (string) $datano)>{{ $datano }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="arrival_date">تاريخ الوصول</label>
                            <input id="arrival_date" name="arrival_date" type="date"
                                value="{{ old('arrival_date', $landShipping->arrival_date?->format('Y-m-d')) }}"
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="exit_date">تاريخ الخروج</label>
                            <input id="exit_date" name="exit_date" type="date"
                                value="{{ old('exit_date', $landShipping->exit_date?->format('Y-m-d')) }}"
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="documents_type">نوع المستندات</label>
                            <select id="documents_type" name="documents_type" class="mt-2 w-full rounded-md border-slate-300">
                                <option value="">اختر النوع</option>
                                <option value="أصل" @selected(old('documents_type', $landShipping->documents_type) == 'أصل')>أصل</option>
                                <option value="صورة" @selected(old('documents_type', $landShipping->documents_type) == 'صورة')>صورة</option>
                                <option value="أصل + افراج" @selected(old('documents_type', $landShipping->documents_type) == 'أصل + افراج')>أصل + إفراج</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700" for="documents_sent_date">تاريخ إرسال المستندات</label>
                            <input id="documents_sent_date" name="documents_sent_date" type="date"
                                value="{{ old('documents_sent_date', $landShipping->documents_sent_date?->format('Y-m-d')) }}"
                                class="mt-2 w-full rounded-md border-slate-300">
                        </div>
                    </div>
                </section>

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
                                            {{ in_array($document->id, old('attached_documents', $landShipping->attachedDocuments->pluck('id')->toArray())) ? 'checked' : '' }}
                                            class="text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                                        <span class="text-sm text-slate-700">{{ $document->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @if($activeDocuments->isEmpty())
                                <p class="text-sm text-slate-500">لا توجد مستندات مفعلة. يمكنك إضافتها من <a href="{{ route('admin.documents.index') }}" class="text-blue-600 hover:underline">الإعدادات</a>.</p>
                            @endif
                        </div>

                        @php
                            $documentsToDelete = old('documents_to_delete', []);
                        @endphp
                        <div class="pt-4 border-t border-slate-200">
                            <p class="mb-3 text-sm font-medium text-slate-700">المستندات الحالية</p>
                            <div class="space-y-2">
                                @forelse($landShipping->documents as $document)
                                    <label class="flex items-center justify-between gap-3 p-3 rounded-lg border border-slate-200">
                                        <span class="flex items-center gap-3">
                                            <input type="checkbox" name="documents_to_delete[]" value="{{ $document->id }}"
                                                @checked(in_array($document->id, $documentsToDelete))
                                                class="text-red-600 rounded border-slate-300 focus:ring-red-500">
                                            <span class="text-sm text-slate-700">{{ $document->original_name }}</span>
                                        </span>
                                        <a class="text-xs text-blue-600 hover:underline"
                                            href="{{ route('road-shipments.documents.download', $document) }}" download>
                                            تحميل
                                        </a>
                                    </label>
                                @empty
                                    <p class="text-sm text-slate-500">لا توجد مستندات مرفقة.</p>
                                @endforelse
                            </div>
                            @if($landShipping->documents->isNotEmpty())
                                <p class="mt-2 text-xs text-slate-500">حدد المستندات التي ترغب في حذفها وسيتم حذفها عند حفظ التعديلات.</p>
                            @endif
                        </div>

                        <div class="pt-4 border-t border-slate-200">
                            <label class="block text-sm font-medium text-slate-700" for="documents_zip">رفع المستندات</label>
                            <input id="documents_zip" name="documents_zip[]" type="file" multiple accept=".zip,.pdf"
                                class="mt-2 w-full text-sm text-slate-700">
                            <p class="mt-2 text-xs text-slate-500">يمكن رفع أكثر من ملف (PDF/ZIP). الحد الأقصى 50MB لكل ملف.</p>
                        </div>
                    </div>
                </section>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('road-shipments.index') }}" class="px-4 py-2 border rounded-md text-slate-700">إلغاء</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">تحديث</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
