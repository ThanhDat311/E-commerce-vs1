<?php

namespace App\Http\Controllers\Admin\AI;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\AiMicroserviceClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ContentGenerationController extends Controller
{
    public function __construct(protected AiMicroserviceClient $aiClient) {}

    /**
     * Display products with missing or short descriptions.
     */
    public function index(): View
    {
        $productsWithoutDesc = Product::query()
            ->whereNull('description')
            ->orWhere('description', '')
            ->orWhereRaw('LENGTH(description) < 100')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total' => Product::count(),
            'missing_desc' => Product::whereNull('description')->orWhere('description', '')->count(),
            'short_desc' => Product::whereRaw('LENGTH(description) < 100')->whereNotNull('description')->where('description', '!=', '')->count(),
            'complete' => Product::whereNotNull('description')->where('description', '!=', '')->whereRaw('LENGTH(description) >= 100')->count(),
        ];

        return view('pages.admin.ai-content-generation.index', compact('productsWithoutDesc', 'stats'));
    }

    /**
     * Generate AI description for a product and save it.
     */
    public function generate(Request $request, Product $product): RedirectResponse
    {
        try {
            $aiDescription = $this->aiClient->generateProductDescription([
                'name' => $product->name,
                'price' => $product->price,
                'category' => $product->category?->name ?? 'General',
            ]);

            if ($aiDescription) {
                $product->description = $aiDescription;
                $product->save();

                return back()->with('success', "Description generated for \"{$product->name}\".");
            }

            return back()->with('error', 'AI service did not return a description. Please check the connection.');
        } catch (\Throwable $e) {
            Log::error('[ContentGeneration] Failed to generate description.', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'AI service is unavailable. Please try again later.');
        }
    }
}
