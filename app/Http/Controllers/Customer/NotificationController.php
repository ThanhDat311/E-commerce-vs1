<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    private const DEFAULT_PREFERENCES = [
        'order_updates' => true,
        'promotions' => false,
        'newsletter' => false,
        'security_alerts' => true,
        'email_notifications' => true,
        'sms_notifications' => false,
        'push_notifications' => false,
    ];

    public function settings()
    {
        $user = Auth::user();
        $preferences = array_merge(self::DEFAULT_PREFERENCES, $user->notification_preferences ?? []);

        return view('profile.notifications.index', compact('user', 'preferences'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'order_updates' => ['nullable'],
            'promotions' => ['nullable'],
            'newsletter' => ['nullable'],
            'security_alerts' => ['nullable'],
            'email_notifications' => ['nullable'],
            'sms_notifications' => ['nullable'],
            'push_notifications' => ['nullable'],
        ]);

        $preferences = [];
        foreach (array_keys(self::DEFAULT_PREFERENCES) as $key) {
            $preferences[$key] = isset($validated[$key]) && $validated[$key];
        }

        Auth::user()->update(['notification_preferences' => $preferences]);

        return redirect()->back()->with('success', 'Notification preferences saved.');
    }
}
