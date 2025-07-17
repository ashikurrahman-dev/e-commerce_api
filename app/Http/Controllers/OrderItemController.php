<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderItemController extends Controller
{
    public function index()
    {
        try {
            $orders = Order::where('user_id', Auth::id())
                ->with('orderItems.product')
                ->latest()
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $orders
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve orders.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store()
    {
        try {
            DB::beginTransaction();

            $userId = Auth::id();
            $carts = Cart::where('user_id', $userId)->with('product')->get();


            if ($carts->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cart is empty.'
                ], 400);
            }
            $order = Order::create([
                'user_id' => $userId,
                'status' => 'pending'
            ]);

            foreach ($carts as $cart) {
                $totalPrice = $cart->product->price * $cart->quantity;
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'quantity' => $cart->quantity,
                    'price' => $totalPrice
                ]);
            }

            Cart::where('user_id', $userId)->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Order placed successfully.',
                'data' => $order->load('orderItems.product')
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to place order.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $order = Order::where('id', $id)
                ->where('user_id', Auth::id())
                ->with('orderItems.product')
                ->firstOrFail();

            return response()->json([
                'status' => 'success',
                'data' => $order
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found.',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}
