<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        try {
            $cart = Cart::with('product')
                ->where('user_id', Auth::id())
                ->latest()
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $cart
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Failed to load cart.',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'product_id' => ['required', 'exists:products,id'],
                'quantity' => ['required', 'integer', 'min:0']
            ]);

            $cart = Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Product added to cart',
                'data' => $cart
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Failed to add product to cart.',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'quantity' => ['required', 'integer', 'min:1']
            ]);

            $item = Cart::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $item->update([
                'quantity' => $request->quantity
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Cart item updated.',
                'data' => $item
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Failed to update cart item.',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $cart = Cart::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $cart->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Cart item removed.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Failed to delete cart item.',
                'error' => $e->getMessage()
            ]);
        }
    }
}
