<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingLine;
use Illuminate\Http\Request;

class ShippingLineController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view shipping lines')->only(['index', 'show']);
        $this->middleware('permission:create shipping lines')->only(['create', 'store']);
        $this->middleware('permission:edit shipping lines')->only(['edit', 'update']);
        $this->middleware('permission:delete shipping lines')->only(['destroy']);
    }

    public function index()
    {
        $shippingLines = ShippingLine::latest()->paginate(10);
        return view('admin.shipping_lines.index', compact('shippingLines'));
    }

    public function create()
    {
        return view('admin.shipping_lines.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'transport_type' => 'required|in:sea,air,land',
            'company_name' => 'nullable|string|max:255',
            'code' => 'nullable|string|max:255|unique:shipping_line,code',
            'time' => 'nullable|integer',
            'contact_email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
        ]);

        ShippingLine::create($validated);

        return redirect()->route('admin.shipping-lines.index')
            ->with('status', 'تم إضافة الخط الملاحي بنجاح');
    }

    public function edit(ShippingLine $shippingLine)
    {
        return view('admin.shipping_lines.edit', compact('shippingLine'));
    }

    public function update(Request $request, ShippingLine $shippingLine)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'transport_type' => 'required|in:sea,air,land',
            'company_name' => 'nullable|string|max:255',
            'code' => 'nullable|string|max:255|unique:shipping_line,code,' . $shippingLine->id,
            'time' => 'nullable|integer',
            'contact_email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
        ]);

        $shippingLine->update($validated);

        return redirect()->route('admin.shipping-lines.index')
            ->with('status', 'تم تحديث الخط الملاحي بنجاح');
    }

    public function destroy(ShippingLine $shippingLine)
    {
        $shippingLine->delete();

        return redirect()->route('admin.shipping-lines.index')
            ->with('status', 'تم حذف الخط الملاحي بنجاح');
    }
}
