<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">إدارة الأدوار</h2>
            <a href="{{ route('admin.roles.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                دور جديد
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if(session('status'))
                <div class="bg-emerald-50 text-emerald-800 px-4 py-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6 overflow-x-auto">
                <table class="min-w-full text-sm text-right">
                    <thead>
                    <tr class="text-gray-500 border-b">
                        <th class="py-2">الدور</th>
                        <th class="py-2">الصلاحيات</th>
                        <th class="py-2">إجراءات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($roles as $role)
                        <tr class="border-b last:border-0">
                            <td class="py-2 font-medium text-gray-800">{{ $role->name }}</td>
                            <td class="py-2">
                                @foreach($role->permissions as $permission)
                                    <span class="inline-block bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">
                                        {{ $permission->name }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="py-2 flex items-center gap-2 justify-end">
                                <a href="{{ route('admin.roles.edit', $role) }}" class="text-blue-600 hover:underline">تعديل</a>
                                <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" onsubmit="return confirm('هل تريد حذف الدور؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline" type="submit">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-4 text-center text-gray-500">لا توجد أدوار بعد.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
