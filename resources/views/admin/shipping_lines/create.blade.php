<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('إضافة خط ملاحي جديد') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-8 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('admin.shipping-lines.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div class="col-span-1 md:col-span-2">
                                <x-input-label for="name" :value="__('الاسم')" class="mb-1" />
                                <x-text-input id="name" class="block w-full" type="text" name="name"
                                    :value="old('name')" required autofocus placeholder="اسم الخط الملاحي" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Transport Type -->
                            <div>
                                <x-input-label for="transport_type" :value="__('نوع الخط الملاحي')" class="mb-1" />
                                <select id="transport_type" name="transport_type" required
                                    class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="sea" @selected(old('transport_type') === 'sea')>بحري</option>
                                    <option value="air" @selected(old('transport_type') === 'air')>جوي</option>
                                    <option value="land" @selected(old('transport_type') === 'land')>بري</option>
                                </select>
                                <x-input-error :messages="$errors->get('transport_type')" class="mt-2" />
                            </div>

                            <!-- Company Name -->
                            <div>
                                <x-input-label for="company_name" :value="__('اسم الشركة')" class="mb-1" />
                                <x-text-input id="company_name" class="block w-full" type="text" name="company_name"
                                    :value="old('company_name')" placeholder="الشركة المالكة" />
                                <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
                            </div>

                            <!-- Code -->
                            <div>
                                <x-input-label for="code" :value="__('الكود التعريفى')" class="mb-1" />
                                <x-text-input id="code" class="block w-full" type="text" name="code"
                                    :value="old('code')" placeholder="مثال: MSC, MAERSK" />
                                <x-input-error :messages="$errors->get('code')" class="mt-2" />
                            </div>

                            <!-- Time -->
                            <div>
                                <x-input-label for="time" :value="__('الوقت المتوقع (أيام)')" class="mb-1" />
                                <x-text-input id="time" class="block w-full" type="number" name="time"
                                    :value="old('time')" placeholder="0" />
                                <x-input-error :messages="$errors->get('time')" class="mt-2" />
                            </div>

                            <!-- Phone -->
                            <div>
                                <x-input-label for="phone" :value="__('رقم الهاتف')" class="mb-1" />
                                <x-text-input id="phone" class="block w-full" type="text" name="phone"
                                    :value="old('phone')" placeholder="رقم للتواصل" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div class="col-span-1 md:col-span-2">
                                <x-input-label for="contact_email" :value="__('البريد الإلكتروني')" class="mb-1" />
                                <x-text-input id="contact_email" class="block w-full" type="email" name="contact_email"
                                    :value="old('contact_email')" placeholder="example@shipping.com" />
                                <x-input-error :messages="$errors->get('contact_email')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100 mt-6">
                            <a href="{{ route('admin.shipping-lines.index') }}"
                                class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('إلغاء') }}
                            </a>
                            <x-primary-button>
                                {{ __('حفظ البيانات') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
