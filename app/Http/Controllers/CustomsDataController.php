<?php

namespace App\Http\Controllers;

use App\Models\CustomsData;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomsDataController extends Controller
{
    public function index(Request $request)
    {
        $query = CustomsData::query()->withCount('shipments');

        if ($request->filled('search')) {
            $search = trim((string) $request->get('search'));
            $query->where('datano', 'like', "%{$search}%");
        }

        if ($request->filled('date_from')) {
            $query->whereDate('datacreate', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('datacreate', '<=', $request->date_to);
        }

        $dataRows = $query
            ->orderByDesc('datacreate')
            ->paginate(15)
            ->withQueryString();

        return view('customs.index', compact('dataRows'));
    }

    public function create()
    {
        return view('customs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'datano' => ['required', 'integer', 'min:1', 'unique:data,datano'],
            'datacreate' => ['required', 'date'],
            'state' => ['nullable', 'integer'],
        ]);

        CustomsData::create($data);

        return redirect()->route('customs.index')->with('status', 'تم إضافة البيان الجمركي بنجاح.');
    }

    public function edit(CustomsData $customsData)
    {
        return view('customs.edit', compact('customsData'));
    }

    public function update(Request $request, CustomsData $customsData)
    {
        $data = $request->validate([
            'datano' => ['required', 'integer', 'min:1', Rule::unique('data', 'datano')->ignore($customsData->id)],
            'datacreate' => ['required', 'date'],
            'state' => ['nullable', 'integer'],
        ]);

        $customsData->update($data);

        return redirect()->route('customs.index')->with('status', 'تم تحديث البيان الجمركي بنجاح.');
    }

    public function destroy(CustomsData $customsData)
    {
        $customsData->delete();

        return redirect()->route('customs.index')->with('status', 'تم حذف البيان الجمركي.');
    }
}
