<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class TestLogout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-logout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test logout functionality with CSRF token';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing logout functionality...');

        // First, get CSRF token
        $this->info('Getting CSRF token...');
        $response = Http::get('http://127.0.0.1:8080/test-csrf');

        if ($response->failed()) {
            $this->error('Failed to get CSRF token: ' . $response->status());
            return;
        }

        $data = $response->json();
        $csrfToken = $data['csrf_token'] ?? null;

        if (!$csrfToken) {
            $this->error('No CSRF token found in response');
            return;
        }

        $this->info('CSRF Token: ' . $csrfToken);

        // Now try to logout
        $this->info('Attempting logout...');
        $logoutResponse = Http::withHeaders([
            'X-CSRF-TOKEN' => $csrfToken,
            'Referer' => 'http://127.0.0.1:8080',
        ])->post('http://127.0.0.1:8080/logout');

        $this->info('Logout response status: ' . $logoutResponse->status());

        if ($logoutResponse->status() === 302) {
            $this->info('Logout successful! (Redirect response)');
        } elseif ($logoutResponse->status() === 200) {
            $this->info('Logout successful!');
        } else {
            $this->error('Logout failed: ' . $logoutResponse->body());
        }
    }
}
