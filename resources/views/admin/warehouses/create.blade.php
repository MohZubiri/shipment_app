<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">إضافة مخزن جديد</h2>
                <p class="text-sm text-slate-500">إدخال بيانات المخزن الجديد</p>
            </div>
            <a href="{{ route('admin.warehouses.index') }}" class="text-sm text-slate-700 hover:text-slate-900">
                إلغاء
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="p-6 bg-white border shadow-sm sm:rounded-xl border-slate-100">
                <form method="POST" action="{{ route('admin.warehouses.store') }}">
                    @csrf

                    <div class="space-y-6">
                        <!-- Basic Information -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <x-input-label for="name" value="اسم المخزن" />
                                <input type="text" id="name" name="name" class="block mt-1 w-full rounded-lg shadow-sm border-slate-300 focus:border-blue-500 focus:ring-blue-500" value="{{ old('name') }}" required>
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="code" value="كود المخزن" />
                                <input type="text" id="code" name="code" class="block mt-1 w-full rounded-lg shadow-sm border-slate-300 focus:border-blue-500 focus:ring-blue-500" value="{{ old('code') }}" required>
                                <x-input-error :messages="$errors->get('code')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="location" value="الموقع" />
                            <input type="text" id="location" name="location" class="block mt-1 w-full rounded-lg shadow-sm border-slate-300 focus:border-blue-500 focus:ring-blue-500" value="{{ old('location') }}" placeholder="مثال: جدة، المملكة العربية السعودية">
                            <x-input-error :messages="$errors->get('location')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="address" value="العنوان" />
                            <textarea id="address" name="address" rows="3" class="block mt-1 w-full rounded-lg shadow-sm border-slate-300 focus:border-blue-500 focus:ring-blue-500" placeholder="العنوان التفصيلي للمخزن">{{ old('address') }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <x-input-label for="capacity" value="السعة (عدد الحاويات)" />
                                <input type="number" id="capacity" name="capacity" min="0" class="block mt-1 w-full rounded-lg shadow-sm border-slate-300 focus:border-blue-500 focus:ring-blue-500" value="{{ old('capacity') }}" placeholder="الحد الأقصى للحاويات">
                                <x-input-error :messages="$errors->get('capacity')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="is_active" value="الحالة" />
                                <div class="mt-2">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <span class="mr-2 text-sm text-slate-700">نشط</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <x-input-label for="notes" value="ملاحظات" />
                            <textarea id="notes" name="notes" rows="3" class="block mt-1 w-full rounded-lg shadow-sm border-slate-300 focus:border-blue-500 focus:ring-blue-500" placeholder="أي ملاحظات إضافية...">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <!-- Submit -->
                        <div class="flex gap-4 justify-end items-center">
                            <a href="{{ route('admin.warehouses.index') }}" class="px-4 py-2 text-slate-700 hover:text-slate-900">
                                إلغاء
                            </a>
                            <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                حفظ المخزن
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
