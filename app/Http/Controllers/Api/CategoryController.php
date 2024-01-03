<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function createCategory(Request $request)
    {
        $category = new Category();
        $category = $request->title;
        $category = $request->color;
        $category->save();
    }
}
