<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function authorizeSystem(Request $request): void
    {
        if (!$request->user()?->is_system) {
            abort(403);
        }
    }

    public function edit(Request $request)
    {
        $this->authorizeSystem($request);

        $setting = Setting::first() ?? Setting::create(['system_name' => 'نظام الشحنات']);

        return view('admin.site_settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $this->authorizeSystem($request);

        $data = $request->validate([
            'system_name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        $setting = Setting::first() ?? new Setting();
        $setting->system_name = $data['system_name'];

        if ($request->hasFile('logo')) {
            if ($setting->logo_path && Storage::disk('public')->exists($setting->logo_path)) {
                Storage::disk('public')->delete($setting->logo_path);
            }
            $setting->logo_path = $request->file('logo')->store('settings', 'public');
        }

        $setting->save();

        return back()->with('status', 'تم حفظ الإعدادات بنجاح.');
    }
}
