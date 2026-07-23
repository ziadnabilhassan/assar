<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::with(['user', 'product', 'variant', 'design'])->latest()->get();
        return view('admin.cart-items.index', compact('cartItems'));
    }

    public function show($id)
    {
        $cartItem = CartItem::with(['user', 'product', 'variant', 'design'])->findOrFail($id);
        return view('admin.cart-items.show', compact('cartItem'));
    }

    public function destroy(Request $request, $id)
    {
        $cartItem = CartItem::findOrFail($request->id);
        $cartItem->delete();

        return back();
    }
}
