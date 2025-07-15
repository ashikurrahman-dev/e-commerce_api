<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Exception;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Product::with('category');

            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            if ($request->has('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            if ($request->has('min_price')) {
                $query->where('price', '>=', $request->min_price);
            }

            if ($request->has('max_price')) {
                $query->where('price', '<=', $request->max_price);
            }

            $products = $query->latest()->paginate(20);

            return response()->json([
                'status' => 'success',
                'data' => $products
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load product list.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $product = Product::with('category')->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $product
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found or failed to load.',
                'error' => $e->getMessage()
            ], 404);
        }
    }


}
