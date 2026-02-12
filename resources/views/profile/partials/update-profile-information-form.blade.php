<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            المعلومات الشخصية
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            حدث اسمك وصورتك. البريد الإلكتروني ثابت ولا يمكن تعديله من هنا.
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data" x-data="{ preview: '{{ $user->avatar_url }}' }">
        @csrf
        @method('patch')

        <div class="flex items-center gap-4 p-4 rounded-lg bg-gradient-to-r from-indigo-50 to-slate-50 border border-indigo-100">
            <div class="relative">
                <img x-bind:src="preview" alt="Avatar" class="h-20 w-20 rounded-full border-2 border-white shadow" />
                <div class="absolute -bottom-2 -right-2">
                    <label class="cursor-pointer inline-flex items-center px-3 py-1 text-xs font-semibold bg-indigo-600 text-white rounded-full hover:bg-indigo-700">
                        {{ __('تغيير الصورة') }}
                        <input type="file" name="avatar" accept="image/*" class="hidden" @change="if($event.target.files[0]){ preview = URL.createObjectURL($event.target.files[0]); }">
                    </label>
                </div>
            </div>
            <div class="space-y-1">
                <p class="text-sm text-slate-600">ارفع صورة واضحة بحد أقصى 2 ميغابايت (jpg، png).</p>
                <x-input-error class="mt-1" :messages="$errors->get('avatar')" />
            </div>
        </div>

        <div>
            <x-input-label for="name" value="الاسم" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="البريد الإلكتروني (غير قابل للتعديل)" />
            <x-text-input id="email" type="email" class="mt-1 block w-full bg-slate-50 border-slate-200" :value="$user->email" disabled />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>حفظ</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >تم الحفظ.</p>
            @endif
        </div>
    </form>
</section>
