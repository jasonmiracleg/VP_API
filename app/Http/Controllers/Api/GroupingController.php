<?php

namespace App\Http\Controllers\Api;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class GroupingController extends Controller
{
    //
    public function addUserToGroup(Request $request, $groupId, $userId)
    {
        $request->validate([
            'is_accepted' => 'required|in:0,1,2',
        ]);

        $group = Group::findOrFail($groupId);
        $user = User::findOrFail($userId);

        // Check if the user is already in the group
        if (!$group->grouping() == $user->id) {
            // Add the user to the group with the specified status
            $group->grouping()->attach($user, ['is_accepted' => $request->is_accepted]);

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => "User added to group with status: {$request->is_accepted}",
                'data' => $group
            ]);
        } else {
            return response()->json([
                'status' => Response::HTTP_CONFLICT,
                'message' => "User is already in the group",
                'data' => $group
            ]);
        }
    }

    public function getMembers(Group $group)
    {
        $members = $group->grouping()->where('is_accepted', '1')->get();
        return [
            'status' => Response::HTTP_OK,
            'message' => 'Success',
            'data' => $members,
        ];
    }
}
