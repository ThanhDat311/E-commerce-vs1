<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDealRequest;
use App\Models\Category;
use App\Models\Deal;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DealController extends Controller
{
    public function index(): View
    {
        $deals = Deal::with(['creator', 'vendor'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('pages.admin.deals.index', compact('deals'));
    }

    public function create(): View
    {
        $products = Product::orderBy('name')->get(['id', 'name']);
        $categories = Category::orderBy('name')->get(['id', 'name']);

        return view('pages.admin.deals.form', compact('products', 'categories'));
    }

    public function store(StoreDealRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();
        $data['status'] = $data['status'] ?? 'draft';

        $deal = Deal::create($data);

        if (! empty($data['product_ids'])) {
            $deal->products()->sync($data['product_ids']);
        }

        if (! empty($data['category_ids'])) {
            $deal->categories()->sync($data['category_ids']);
        }

        return redirect()->route('admin.deals.index')->with('success', 'Deal created successfully.');
    }

    public function edit(Deal $deal): View
    {
        $products = Product::orderBy('name')->get(['id', 'name']);
        $categories = Category::orderBy('name')->get(['id', 'name']);

        return view('pages.admin.deals.form', compact('deal', 'products', 'categories'));
    }

    public function update(StoreDealRequest $request, Deal $deal): RedirectResponse
    {
        $data = $request->validated();

        $deal->update($data);

        if (array_key_exists('product_ids', $data)) {
            $deal->products()->sync($data['product_ids'] ?? []);
        }

        if (array_key_exists('category_ids', $data)) {
            $deal->categories()->sync($data['category_ids'] ?? []);
        }

        return redirect()->route('admin.deals.index')->with('success', 'Deal updated successfully.');
    }

    public function destroy(Deal $deal): RedirectResponse
    {
        $deal->delete();

        return redirect()->route('admin.deals.index')->with('success', 'Deal deleted.');
    }

    public function approve(Deal $deal): RedirectResponse
    {
        if ($deal->status !== 'pending') {
            return back()->with('error', 'Only pending deals can be approved.');
        }

        $deal->update([
            'status' => 'active',
            'approved_by' => auth()->id(),
        ]);

        return redirect()->route('admin.deals.index')->with('success', 'Deal approved and activated.');
    }

    public function toggleStatus(Deal $deal): RedirectResponse
    {
        $newStatus = $deal->status === 'active' ? 'draft' : 'active';
        $deal->update(['status' => $newStatus]);

        return back()->with('success', "Deal status changed to {$newStatus}.");
    }
}
