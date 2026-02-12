<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-slate-900 leading-tight">الملف الشخصي</h2>
                <p class="text-sm text-slate-500 mt-1">حدث بياناتك وصورتك من مكان واحد.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="p-6 sm:p-8 bg-white shadow-sm border border-slate-100 rounded-2xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="space-y-6">
                <div class="p-6 sm:p-8 bg-white shadow-sm border border-slate-100 rounded-2xl">
                    @include('profile.partials.update-password-form')
                </div>

                <div class="p-6 sm:p-8 bg-white shadow-sm border border-rose-100 rounded-2xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
