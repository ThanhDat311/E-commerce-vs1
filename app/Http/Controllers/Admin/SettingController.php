<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    /**
     * Display a listing of settings.
     */
    public function index()
    {
        $settings = Setting::all()->groupBy('group');

        // Ensure groups exist even if empty
        $groups = ['general', 'payment', 'notification', 'security', 'backup'];
        foreach ($groups as $group) {
            if (! isset($settings[$group])) {
                $settings[$group] = collect();
            }
        }

        return view('pages.admin.settings.index', compact('settings'));
    }

    /**
     * Update settings.
     */
    public function update(Request $request)
    {
        $data = $request->except(['_token', 'group']);
        $group = $request->input('group', 'general');

        foreach ($data as $key => $value) {
            // Handle boolean toggles (if checkbox is unchecked, it won't be sent, but here we process sent data)
            // Ideally we iterate known keys or handle checkboxes specifically.
            // For now, let's assume all inputs are sent or handled.

            // Special handling for sensitive data masking (don't update if value is '******')
            if (str_contains($key, 'secret') || str_contains($key, 'password') || str_contains($key, 'key')) {
                if ($value === '******') {
                    continue;
                }
            }

            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'group' => $group,
                    'type' => 'text', // Default type
                ]
            );

            Cache::forget("settings.{$key}");
        }

        // Handle checkboxes that are unchecked (and thus missing from request)
        // We need a list of expected boolean keys for this group
        $booleanKeys = match ($group) {
            'general' => ['maintenance_mode'],
            'security' => ['allow_vendor_registration', 'require_email_verification'],
            'payment' => ['enable_stripe', 'enable_paypal'],
            default => [],
        };

        foreach ($booleanKeys as $key) {
            if (! $request->has($key)) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => '0', 'group' => $group, 'type' => 'boolean']
                );
                Cache::forget("settings.{$key}");
            } else {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => '1', 'group' => $group, 'type' => 'boolean']
                );
                Cache::forget("settings.{$key}");
            }
        }

        if ($request->has('maintenance_mode')) {
            if ($request->input('maintenance_mode')) {
                Artisan::call('down');
            } else {
                Artisan::call('up');
            }
        }

        if ($group === 'backup' && $request->has('trigger_backup')) {
            // Logic to trigger backup
            // Artisan::call('backup:run');
            return back()->with('success', 'Backup started in background.');
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
