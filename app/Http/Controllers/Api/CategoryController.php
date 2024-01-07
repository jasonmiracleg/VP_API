<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ToDoListResource;
use App\Models\Category;
use App\Models\ToDoList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    public function ListCategory(): array
    {
        $categories = Category::all();
        return [
            "status" => Response::HTTP_OK,
            "message" => "Success",
            "data" => CategoryResource::collection($categories)
        ];
    }

    public function categoryWithToDoList(Category $category)
    {
        $toDoList = $category->customToDoLists;
        return [
            "status" => Response::HTTP_OK,
            "message" => "Success",
            "data" => ToDoListResource::collection($toDoList)
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
        $category = Category::findOrFail($request->id);
        $category->customToDoLists()->detach();
        $category->delete();

        return [
            "status" => Response::HTTP_OK,
            "message" => "Success",
            "data" => $category
        ];
    }

    public function setCategory(Request $request, ToDoList $toDoList)
    {
        try {
            $categoryIDs = $request->input('category_ids', []);

            $toDoList->categories()->sync($categoryIDs);

            return [
                "status" => Response::HTTP_OK,
                "message" => "Success",
                "data" => $toDoList->load('categories'),
            ];
        } catch (Exception $e) {
            return [
                "status" => Response::HTTP_INTERNAL_SERVER_ERROR,
                "message" => $e->getMessage(),
                "data" => []
            ];
        }
    }
}
