<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function getCategories()
    {
        return CategoryResource::collection(Category::with('tasks')->orderBy('name', 'asc')->get());
    }

    public function store(Request $request)
    {
        $name = $request->name;
        $category_exists = Category::where('name', $request->input('name'))->exists();
        if($category_exists) {
            return response()->json([
                'message' => `${name} already exist`
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        $category = Category::create([
            'user_id' => Auth::id(),
            'name' => $request->input('name'),
        ]);
        return new CategoryResource($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->noContent();
    }
}
