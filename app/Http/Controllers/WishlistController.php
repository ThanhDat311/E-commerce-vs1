<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::where('user_id', Auth::id())
            ->with('product')
            ->get();

        return view('wishlist', compact('wishlists'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        Wishlist::firstOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id
        ]);

        return response()->json(['success' => true, 'message' => 'Product added to wishlist']);
    }

    public function remove($productId)
    {
        Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->delete();

        return redirect()->route('wishlist.index')->with('success', 'Product removed from wishlist');
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return redirect()->back()->with('success', 'Product removed from wishlist');
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id
            ]);
            return redirect()->back()->with('success', 'Product added to wishlist');
        }
    }
}
