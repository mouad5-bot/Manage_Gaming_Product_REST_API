<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class FilterProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    
    public function filter(Request $request){
        $_query = Product::with(['category']);

            $data = $request->category;

            $_query->whereHas('category', function($products) use($data){
                $products->where('name', 'like', '%' . $data . '%');
            });   
    
        $products = $_query->get();
        return response()->json([
            'data'=>$products,
        ], 200);
    }

}


