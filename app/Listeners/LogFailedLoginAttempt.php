<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;

class LogFailedLoginAttempt
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Failed $event): void
    {
        if ($event->user) {
            \App\Models\AuthLog::create([
                'user_id' => $event->user->id,
                'session_id' => request()->hasSession() ? request()->session()->getId() : 'no-session',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'risk_score' => 0.1, // Default low score for a simple failed pass, logic relies on velocity count
                'risk_level' => 'low',
                'auth_decision' => 'passive_auth_allow',
                'is_successful' => false,
                'reasons' => ['Invalid password attempt'],
            ]);
        }
    }
}
