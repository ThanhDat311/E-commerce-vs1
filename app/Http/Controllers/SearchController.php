<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q');

        if (! $query) {
            return redirect()->route('shop.index');
        }

        $products = Product::where('name', 'LIKE', '%'.$query.'%')
            ->orWhere('description', 'LIKE', '%'.$query.'%')
            ->paginate(12);

        return view('shop.index', compact('products', 'query'));
    }
}
