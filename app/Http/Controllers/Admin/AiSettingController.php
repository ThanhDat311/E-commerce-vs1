<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiSettingController extends Controller
{
    public function index()
    {
        $settings = [
            'ai_base_url' => \App\Models\Setting::get('ai_base_url', config('services.ai_microservice.url', 'http://localhost:8000')),
            'ai_api_key' => \App\Models\Setting::get('ai_api_key', ''),
            'ai_timeout' => \App\Models\Setting::get('ai_timeout', config('services.ai_microservice.timeout', 3)),
            'strict_mode' => \App\Models\Setting::get('strict_mode', '0'),
            'auto_apply_price_suggestions' => \App\Models\Setting::get('auto_apply_price_suggestions', '0'),
        ];

        return view('pages.admin.ai-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'ai_base_url' => ['required', 'url'],
            'ai_api_key' => ['nullable', 'string'],
            'ai_timeout' => ['required', 'numeric', 'min:1', 'max:30'],
            'strict_mode' => ['nullable', 'boolean'],
            'auto_apply_price_suggestions' => ['nullable', 'boolean'],
        ]);

        \App\Models\Setting::set('ai_base_url', $request->input('ai_base_url'), 'ai');
        \App\Models\Setting::set('ai_api_key', $request->input('ai_api_key', ''), 'ai');
        \App\Models\Setting::set('ai_timeout', $request->input('ai_timeout'), 'ai');
        \App\Models\Setting::set('strict_mode', $request->input('strict_mode', '0'), 'ai');
        \App\Models\Setting::set('auto_apply_price_suggestions', $request->input('auto_apply_price_suggestions', '0'), 'ai');

        return redirect()->back()->with('success', 'AI settings saved successfully.');
    }

    public function testConnection(): \Illuminate\Http\JsonResponse
    {
        try {
            $url = \App\Models\Setting::get('ai_base_url', config('services.ai_microservice.url', 'http://localhost:8000'));
            $apiKey = \App\Models\Setting::get('ai_api_key', '');

            $request = Http::timeout(3);
            if (! empty($apiKey)) {
                $request->withToken($apiKey);
            }

            $response = $request->get("{$url}/health");

            if ($response->successful()) {
                return response()->json(['status' => 'online', 'message' => 'AI Engine is reachable and healthy.']);
            }

            return response()->json(['status' => 'error', 'message' => "Received HTTP {$response->status()} from the AI Engine."], 200);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return response()->json(['status' => 'offline', 'message' => 'Cannot connect to AI Engine. Is the FastAPI service running?'], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);
        }
    }
}
