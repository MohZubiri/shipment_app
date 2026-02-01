<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">إدارة الصلاحيات</h2>
            <a href="{{ route('admin.permissions.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                صلاحية جديدة
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if(session('status'))
                <div class="bg-emerald-50 text-emerald-800 px-4 py-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full text-sm text-right">
                    <thead>
                    <tr class="text-gray-500 border-b">
                        <th class="py-2">الصلاحية</th>
                        <th class="py-2">إجراءات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($permissions as $permission)
                        <tr class="border-b last:border-0">
                            <td class="py-2">{{ $permission->name }}</td>
                            <td class="py-2 flex items-center gap-2 justify-end">
                                <a href="{{ route('admin.permissions.edit', $permission) }}" class="text-blue-600 hover:underline">تعديل</a>
                                <form method="POST" action="{{ route('admin.permissions.destroy', $permission) }}" onsubmit="return confirm('هل تريد حذف الصلاحية؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline" type="submit">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="py-4 text-center text-gray-500">لا توجد صلاحيات بعد.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
