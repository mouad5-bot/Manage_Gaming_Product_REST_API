<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = JWTAuth::user();
        // dd($user->hasPermissionTo('read products'));
        $Products = Product::orderBy('id')->get();

        return response()->json([
            'status' => 'success',
            'Products' => $Products
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $user = Auth::user();
        $Product = $user->products()->create($request->all());

        return response()->json([
            'status' => true,
            'message' => "Product Created successfully!",
            'Product' => $Product
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $Product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $Product)
    {
        $Product->find($Product->id);
        if (!$Product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($Product, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $Product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $Product)
    {
        $user = Auth::user();

        if(!$user->can('edit all products') && $Product->user_id != $user->id){
            return response()->json([
                'status' => false,
                'message' => "You don't have the permission for edit this Product!"
            ], 200);
        }
        $Product->update($request->all());

        if (!$Product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json([
            'status' => true,
            'message' => "Product Updated successfully!",
            'Product' => $Product
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $Product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $Product)
    {
        $user = Auth::user();

        if(!$user->can('delete all products') && $Product->user_id != $user->id){
            return response()->json([
                'status' => false,
                'message' => "You don't have the permission for delete this Product!"
            ], 200);
        }

        if (!$Product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        $Product->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully'
        ], 200);
    }
}