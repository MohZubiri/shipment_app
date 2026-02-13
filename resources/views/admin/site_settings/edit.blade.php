<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">إعدادات النظام</h2>
                <p class="text-sm text-gray-500 mt-1">تحديث اسم النظام وشعار الواجهة.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-xl p-6 space-y-6 border border-slate-100">
                @if(session('status'))
                    <div class="bg-emerald-50 text-emerald-800 px-4 py-3 rounded-lg">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.site-settings.update') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="system_name">اسم النظام</label>
                        <input id="system_name" name="system_name" type="text" value="{{ old('system_name', $setting->system_name) }}"
                               class="mt-1 w-full rounded-md border-gray-300" required>
                        <x-input-error class="mt-1" :messages="$errors->get('system_name')" />
                    </div>

                    <div class="grid sm:grid-cols-3 gap-4 items-center">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700" for="logo">شعار النظام (اختياري، حد 2MB)</label>
                            <input id="logo" name="logo" type="file" accept="image/*"
                                   class="mt-1 w-full rounded-md border-gray-300">
                            <x-input-error class="mt-1" :messages="$errors->get('logo')" />
                        </div>
                        <div class="justify-self-end">
                            <div class="text-xs text-gray-500 mb-1">معاينة الشعار</div>
                            <div class="h-16 w-16 border rounded-md overflow-hidden bg-slate-50 flex items-center justify-center">
                                @if($setting->logo_url)
                                    <img src="{{ $setting->logo_url }}" alt="Logo" class="h-full w-full object-contain">
                                @else
                                    <span class="text-gray-400 text-xs">لا يوجد</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="backup_emails">ايميلات النسخ الاحتياطي (افصلها بفواصل أو أسطر)</label>
                        <textarea id="backup_emails" name="backup_emails" rows="3" class="mt-1 w-full rounded-md border-gray-300" placeholder="admin@example.com
ops@example.com">{{ old('backup_emails', isset($setting->backup_emails) ? implode('\n', $setting->backup_emails) : '') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">سيتم إرسال نسخة قاعدة البيانات اليومية إلى هذه الإيميلات.</p>
                        <x-input-error class="mt-1" :messages="$errors->get('backup_emails')" />
                    </div>

                    <div class="border-t pt-6">
                        <h3 class="font-semibold text-gray-800 mb-2">استعادة نسخة قاعدة البيانات</h3>
                        <p class="text-sm text-gray-600 mb-3">ارفع ملف النسخة الاحتياطية (.sql أو .sql.gz). سيؤدي ذلك إلى استبدال البيانات الحالية بالكامل، لذا استخدمها بحذر.</p>
                        <input id="restore_backup" name="restore_backup" type="file" accept=".sql,.gz"
                               class="w-full rounded-md border-gray-300">
                        <x-input-error class="mt-1" :messages="$errors->get('restore_backup')" />
                        <p class="text-xs text-amber-600 mt-2">يُفضل أخذ نسخة حالية قبل الاستعادة.</p>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
