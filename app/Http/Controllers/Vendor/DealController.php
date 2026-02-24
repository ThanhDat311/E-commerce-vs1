<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDealRequest;
use App\Models\Category;
use App\Models\Deal;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DealController extends Controller
{
    public function index(): View
    {
        $deals = Deal::where('vendor_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('pages.vendor.deals.index', compact('deals'));
    }

    public function create(): View
    {
        $products = Product::where('vendor_id', Auth::id())
            ->orderBy('name')
            ->get(['id', 'name']);

        $categories = Category::orderBy('name')->get(['id', 'name']);

        return view('pages.vendor.deals.form', compact('products', 'categories'));
    }

    public function store(StoreDealRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['vendor_id'] = Auth::id();
        $data['created_by'] = Auth::id();
        $data['status'] = 'pending';
        $data['apply_scope'] = in_array($data['apply_scope'] ?? '', ['product', 'category'])
            ? $data['apply_scope']
            : 'product';

        $deal = Deal::create($data);

        if (! empty($data['product_ids'])) {
            $ownProductIds = Product::where('vendor_id', Auth::id())
                ->whereIn('id', $data['product_ids'])
                ->pluck('id');

            $deal->products()->sync($ownProductIds);
        }

        if (! empty($data['category_ids'])) {
            $deal->categories()->sync($data['category_ids']);
        }

        return redirect()->route('vendor.deals.index')
            ->with('success', 'Deal submitted for admin approval.');
    }

    public function edit(Deal $deal): View
    {
        $this->authorizeOwner($deal);

        $products = Product::where('vendor_id', Auth::id())->orderBy('name')->get(['id', 'name']);
        $categories = Category::orderBy('name')->get(['id', 'name']);

        return view('pages.vendor.deals.form', compact('deal', 'products', 'categories'));
    }

    public function update(StoreDealRequest $request, Deal $deal): RedirectResponse
    {
        $this->authorizeOwner($deal);

        $data = $request->validated();
        $deal->update($data);

        if (array_key_exists('product_ids', $data)) {
            $ownProductIds = Product::where('vendor_id', Auth::id())
                ->whereIn('id', $data['product_ids'] ?? [])
                ->pluck('id');
            $deal->products()->sync($ownProductIds);
        }

        if (array_key_exists('category_ids', $data)) {
            $deal->categories()->sync($data['category_ids'] ?? []);
        }

        return redirect()->route('vendor.deals.index')->with('success', 'Deal updated.');
    }

    public function destroy(Deal $deal): RedirectResponse
    {
        $this->authorizeOwner($deal);

        if ($deal->status !== 'draft') {
            return back()->with('error', 'Only draft deals can be deleted.');
        }

        $deal->delete();

        return redirect()->route('vendor.deals.index')->with('success', 'Deal deleted.');
    }

    private function authorizeOwner(Deal $deal): void
    {
        if ($deal->vendor_id !== Auth::id()) {
            abort(403, 'You do not own this deal.');
        }
    }
}
