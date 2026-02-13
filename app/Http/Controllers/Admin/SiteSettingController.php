<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

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
            'backup_emails' => ['nullable', 'string'],
            'restore_backup' => ['nullable', 'file', 'mimetypes:application/sql,text/plain,application/x-gzip,application/gzip,application/octet-stream', 'max:51200'], // max 50MB
        ]);

        $setting = Setting::first() ?? new Setting();
        $setting->system_name = $data['system_name'];

        // Parse comma/line separated emails, keep only valid ones
        $emails = collect(preg_split('/[\s,]+/', $data['backup_emails'] ?? ''))
            ->map(fn ($email) => trim($email))
            ->filter()
            ->filter(fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL))
            ->values()
            ->all();
        $setting->backup_emails = $emails;

        if ($request->hasFile('logo')) {
            if ($setting->logo_path && Storage::disk('public')->exists($setting->logo_path)) {
                Storage::disk('public')->delete($setting->logo_path);
            }
            $setting->logo_path = $request->file('logo')->store('settings', 'public');
        }

        $setting->save();

        // Optional: restore DB from uploaded backup (.sql or .sql.gz)
        if ($request->hasFile('restore_backup')) {
            $path = $request->file('restore_backup')->store('restores', 'local');
            $fullPath = storage_path('app/' . $path);
            $db = config('database.connections.mysql');

            $mysqlCmd = sprintf(
                'mysql --user=%s %s --host=%s --port=%s %s',
                escapeshellarg($db['username']),
                $db['password'] !== '' ? '--password=' . escapeshellarg($db['password']) : '',
                escapeshellarg($db['host']),
                escapeshellarg($db['port']),
                escapeshellarg($db['database'])
            );

            $command = str_ends_with($fullPath, '.gz')
                ? sprintf('gunzip -c %s | %s', escapeshellarg($fullPath), $mysqlCmd)
                : sprintf('%s < %s', $mysqlCmd, escapeshellarg($fullPath));

            $process = Process::fromShellCommandline($command);
            $process->setTimeout(300); // 5 minutes
            $process->run();

            if (! $process->isSuccessful()) {
                return back()
                    ->withErrors(['restore_backup' => 'فشل الاستعادة: ' . trim($process->getErrorOutput() ?: $process->getOutput())])
                    ->withInput();
            }
        }

        return back()->with('status', 'تم حفظ الإعدادات بنجاح.');
    }
}
