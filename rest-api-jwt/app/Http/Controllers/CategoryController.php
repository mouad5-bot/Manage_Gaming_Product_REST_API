<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
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
        $Categories = Category::orderBy('id')->get();

        return response()->json([
            'status' => 'success',
            'Categories' => $Categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $Category = Category::create($request->all());

        return response()->json([
            'status' => true,
            'message' => "Category Created successfully!",
            'Category' => $Category
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $Category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $Category)
    {
        $Category->find($Category->id);
        if (!$Category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        return response()->json($Category, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $Category
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCategoryRequest $request, Category $Category)
    {
        $Category->update($request->all());

        if (!$Category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json([
            'status' => true,
            'message' => "Category Updated successfully!",
            'Category' => $Category
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $Category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $Category)
    {
        $Category->delete();

        if (!$Category) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully'
        ], 200);
    }
}