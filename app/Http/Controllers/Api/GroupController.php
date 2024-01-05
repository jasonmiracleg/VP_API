<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\GroupResource;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class GroupController extends Controller
{

    public function listGroup(Request $request): array
    {
        $groups = Group::all();
        return [
            "status" => Response::HTTP_OK,
            "message" => "Success",
            "data" => GroupResource::collection($groups)

        ];
    }

    public function createGroup(Request $request)
    {
        $group = new Group();
        $group->group_name = $request->group_name;
        $group->description = $request->description;
        $group->save();

        return [
            "status" => Response::HTTP_OK,
            "message" => "Success",
            "data" => $group
        ];
    }

    public function deleteGroup(Request $request)
    {
        $group = Group::where("id", $request->id)->first();
        $group->delete();

        return [
            "status" => Response::HTTP_OK,
            "message" => "Success",
            "data" => $group
        ];
    }
}
