<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">تعديل بيان جمركي</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('customs.update', $customsData) }}" class="bg-white shadow-sm sm:rounded-xl p-6 space-y-6 border border-slate-100">
                @csrf
                @method('PUT')

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
                    <label class="block text-sm font-medium text-slate-700" for="datano">رقم البيان الجمركي</label>
                    <input id="datano" name="datano" type="number" value="{{ old('datano', $customsData->datano) }}" required class="mt-2 w-full rounded-md border-slate-300">
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="datacreate">تاريخ البيان</label>
                        <input id="datacreate" name="datacreate" type="date" value="{{ old('datacreate', $customsData->datacreate?->format('Y-m-d')) }}" required class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700" for="state">الحالة</label>
                        <input id="state" name="state" type="number" value="{{ old('state', $customsData->state) }}" class="mt-2 w-full rounded-md border-slate-300">
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('customs.index') }}" class="px-4 py-2 border rounded-md text-slate-700">عودة</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">تحديث</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
