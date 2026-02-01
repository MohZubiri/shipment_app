<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">إضافة مستخدم</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.users.store') }}" class="bg-white shadow-sm sm:rounded-lg p-6 space-y-6">
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

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="name">الاسم</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required class="mt-1 w-full rounded-md border-gray-300">
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="email">البريد الإلكتروني</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" class="mt-1 w-full rounded-md border-gray-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="phone">الهاتف</label>
                        <input id="phone" name="phone" type="text" value="{{ old('phone') }}" class="mt-1 w-full rounded-md border-gray-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="mobile">الجوال</label>
                        <input id="mobile" name="mobile" type="text" value="{{ old('mobile') }}" class="mt-1 w-full rounded-md border-gray-300">
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="password">كلمة المرور</label>
                        <input id="password" name="password" type="password" required class="mt-1 w-full rounded-md border-gray-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="password_confirmation">تأكيد كلمة المرور</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required class="mt-1 w-full rounded-md border-gray-300">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">الأدوار</label>
                    <div class="mt-2 grid gap-2 sm:grid-cols-2">
                        @foreach($roles as $role)
                            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                <input type="checkbox" name="roles[]" value="{{ $role->name }}" class="rounded border-gray-300">
                                {{ $role->name }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border rounded-md text-gray-700">إلغاء</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
