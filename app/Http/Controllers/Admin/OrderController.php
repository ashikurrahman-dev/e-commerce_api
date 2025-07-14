<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderRequest;
use App\Models\Order;
use Exception;

class OrderController extends Controller
{
    public function index(){
        $orders = Order::with('user')->latest()->get();

        return response()->json([
            'status' => 'success',
            'message' => 'All orders list',
            'data' => $orders
        ], 200);
    }

    public function show($id){
        try{
            $order = Order::with('user')->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Order details retrieved successfully.',
                'data' => $order
            ], 200);
        } catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }
    }

    public function updateStatus(OrderRequest $request, $id){
        try{
            $order = Order::findOrFail($id);

            $order->status = $request->status;
            $order->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Order status updated successfully.',
                'data' => $order
            ], 200);
        } catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found.'
            ], 404);
        }
    }
}
