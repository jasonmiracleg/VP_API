<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    public function ListCategory(Request $request): array
    {
        $categories = Category::all();
        return [
            "status" => Response::HTTP_OK,
            "message" => "Success",
            "data" => CategoryResource::collection($categories)
        ];
    }


    public function createCategory(Request $request)
    {
        $category = new Category();
        $category->title = $request->title;
        $category->color = $request->color;
        $category->save();

        return [
            "status" => Response::HTTP_OK,
            "message" => "Success",
            "data" => $category
        ];
    }

    public function deleteCategory(Request $request)
    {
        $category = Category::where("id", $request->id)->first();
        $category->delete();

        return [
            "status" => Response::HTTP_OK,
            "message" => "Success",
            "data" => $category
        ];
    }
}
