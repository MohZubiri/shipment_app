<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('إضافة منفذ جمركي جديد') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-8 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('admin.customs-ports.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div class="col-span-1 md:col-span-2">
                                <x-input-label for="name" :value="__('الاسم')" class="mb-1" />
                                <x-text-input id="name" class="block w-full" type="text" name="name"
                                    :value="old('name')" required autofocus placeholder="اسم المنفذ الجمركي" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Code -->
                            <div>
                                <x-input-label for="code" :value="__('الكود التعريفى')" class="mb-1" />
                                <x-text-input id="code" class="block w-full" type="text" name="code"
                                    :value="old('code')" placeholder="مثال: JED" />
                                <x-input-error :messages="$errors->get('code')" class="mt-2" />
                            </div>

                            <!-- Type -->
                            <div>
                                <x-input-label for="type" :value="__('النوع')" class="mb-1" />
                                <x-text-input id="type" class="block w-full" type="text" name="type"
                                    :value="old('type')" placeholder="مثال: بحري، بري" />
                                <x-input-error :messages="$errors->get('type')" class="mt-2" />
                            </div>

                            <!-- City -->
                            <div>
                                <x-input-label for="city" :value="__('المدينة')" class="mb-1" />
                                <x-text-input id="city" class="block w-full" type="text" name="city"
                                    :value="old('city')" placeholder="اسم المدينة" />
                                <x-input-error :messages="$errors->get('city')" class="mt-2" />
                            </div>

                            <!-- Country -->
                            <div>
                                <x-input-label for="country" :value="__('الدولة')" class="mb-1" />
                                <x-text-input id="country" class="block w-full" type="text" name="country"
                                    :value="old('country')" placeholder="اسم الدولة" />
                                <x-input-error :messages="$errors->get('country')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100 mt-6">
                            <a href="{{ route('admin.customs-ports.index') }}"
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