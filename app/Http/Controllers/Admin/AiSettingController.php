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
            'ai_base_url' => config('services.ai_microservice.url', 'http://localhost:8000'),
            'ai_timeout' => config('services.ai_microservice.timeout', 3),
        ];

        return view('pages.admin.ai-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'ai_base_url' => ['required', 'url'],
            'ai_timeout' => ['required', 'numeric', 'min:1', 'max:30'],
        ]);

        return redirect()->back()->with('success', 'AI settings saved. Update your .env file to persist these values.');
    }

    public function testConnection(): \Illuminate\Http\JsonResponse
    {
        try {
            $url = config('services.ai_microservice.url', 'http://localhost:8000');
            $response = Http::timeout(3)->get("{$url}/health");

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
