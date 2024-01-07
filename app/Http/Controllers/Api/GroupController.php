<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Group;
use App\Models\Grouping;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GroupResource;
use Symfony\Component\HttpFoundation\Response;

class GroupController extends Controller
{

    public function listGroup($userId): array
    {
        $user = User::find($userId);
        $groups = $user->grouping;
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
        Grouping::create([
            'group_id' => $group->id,
            'user_id' => $request->user_id,
            'is_accepted' => '1'
        ]);

        return [
            "status" => Response::HTTP_OK,
            "message" => "Success",
            "data" => $group
        ];
    }

    public function deleteGroup(Request $request)
    {
        $group = Group::where("id", $request->id)->first();
        $group->grouping()->detach();
        $group->delete();

        return [
            "status" => Response::HTTP_OK,
            "message" => "Success",
            "data" => $group
        ];
    }
}
