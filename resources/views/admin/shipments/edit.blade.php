<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('تعديل الشحنة') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-8 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('admin.shipments.update', $shipment) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Name -->
                            <div>
                                <x-input-label for="name" :value="__('اسم الشحنة')" class="mb-1" />
                                <x-text-input id="name" class="block w-full" type="text" name="name"
                                    :value="old('name', $shipment->name)" required autofocus placeholder="اسم الشحنة" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Quantity -->
                            <div>
                                <x-input-label for="quantity" :value="__('الكمية')" class="mb-1" />
                                <x-text-input id="quantity" class="block w-full" type="number" name="quantity"
                                    :value="old('quantity', $shipment->quantity)" required min="0" placeholder="الكمية" />
                                <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                            </div>

                            <!-- Status -->
                            <div>
                                <x-input-label for="status" :value="__('الحالة')" class="mb-1" />
                                <select id="status" name="status"
                                    class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="active" {{ old('status', $shipment->status) === 'active' ? 'selected' : '' }}>نشط</option>
                                    <option value="inactive" {{ old('status', $shipment->status) === 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                    <option value="pending" {{ old('status', $shipment->status) === 'pending' ? 'selected' : '' }}>معلق</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100 mt-6">
                            <a href="{{ route('admin.shipments.index') }}"
                                class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('إلغاء') }}
                            </a>
                            <x-primary-button>
                                {{ __('تحديث البيانات') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
