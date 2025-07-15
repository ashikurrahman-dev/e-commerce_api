<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request){
        $query = Product::with('category');

        if($request->has('search')){
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if($request->has('category_id')){
            $query->where('category_id', $request->category_id);
        }

        if($request->has('min_price')){
            $query->where('price', '>=' , $request->min_price);
        }

        if($request->has('max_price')){
            $query->where('price', '=>' , $request->max_price);
        }

        $products = $query->latest()->paginate(20);

        return response()->json([
            'status' => 'success',
            'data' => $products
        ]);
    }

    
}
