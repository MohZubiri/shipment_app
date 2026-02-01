<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">إضافة دور</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.roles.store') }}" class="bg-white shadow-sm sm:rounded-lg p-6 space-y-6">
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
                    <label class="block text-sm font-medium text-gray-700" for="name">اسم الدور</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required class="mt-1 w-full rounded-md border-gray-300">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">الصلاحيات</label>
                    <div class="mt-2 grid gap-2 sm:grid-cols-2">
                        @foreach($permissions as $permission)
                            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="rounded border-gray-300">
                                {{ $permission->name }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 border rounded-md text-gray-700">إلغاء</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
