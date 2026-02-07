<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">إضافة مرحلة جديدة</h2>
                <p class="text-sm text-slate-500">إنشاء مرحلة ضمن رحلة الشحنة</p>
            </div>
            <a href="{{ route('admin.shipment-stages.index') }}" class="text-sm text-blue-600 hover:text-blue-700">العودة</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white border border-slate-100 shadow-sm sm:rounded-xl">
                <form method="POST" action="{{ route('admin.shipment-stages.store') }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="name" value="الاسم" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name') }}" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="name_en" value="الاسم بالإنجليزية" />
                            <x-text-input id="name_en" name="name_en" type="text" class="mt-1 block w-full" value="{{ old('name_en') }}" />
                            <x-input-error :messages="$errors->get('name_en')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="code" value="الكود" />
                            <x-text-input id="code" name="code" type="text" class="mt-1 block w-full" value="{{ old('code') }}" required />
                            <x-input-error :messages="$errors->get('code')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="order" value="الترتيب" />
                            <x-text-input id="order" name="order" type="number" min="0" class="mt-1 block w-full" value="{{ old('order', 0) }}" required />
                            <x-input-error :messages="$errors->get('order')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="icon" value="الأيقونة (اختياري)" />
                            <x-text-input id="icon" name="icon" type="text" class="mt-1 block w-full" value="{{ old('icon') }}" />
                            <x-input-error :messages="$errors->get('icon')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="color" value="اللون (Hex)" />
                            <x-text-input id="color" name="color" type="text" class="mt-1 block w-full" value="{{ old('color', '#6366f1') }}" />
                            <x-input-error :messages="$errors->get('color')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="description" value="الوصف" />
                        <textarea id="description" name="description" rows="3" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="hidden" name="is_active" value="0">
                        <input id="is_active" name="is_active" type="checkbox" value="1" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label for="is_active" class="text-sm text-slate-700">نشط</label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="hidden" name="needs_containers" value="0">
                        <input id="needs_containers" name="needs_containers" type="checkbox" value="1" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500" {{ old('needs_containers', false) ? 'checked' : '' }}>
                        <label for="needs_containers" class="text-sm text-slate-700">يتطلب إدخال الحاويات (عدد وأرقام)</label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="hidden" name="needs_warehouse" value="0">
                        <input id="needs_warehouse" name="needs_warehouse" type="checkbox" value="1" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500" {{ old('needs_warehouse', false) ? 'checked' : '' }}>
                        <label for="needs_warehouse" class="text-sm text-slate-700">يتطلب اختيار مخزن</label>
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('admin.shipment-stages.index') }}" class="text-sm text-slate-600 hover:text-slate-800">إلغاء</a>
                        <x-primary-button>حفظ المرحلة</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
