<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function getAllUser()
    {
        $users = User::all();
        return $users;
    }

    public function createUser(Request $request)
    {
        try {

            $user = User::create([
                'profile_picture' => $request->profile_picture,
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'born_date' => $request->born_date,
            ]);
            $user->save();

            return [
                'status' => Response::HTTP_OK,
                'message' => "Sign up successful !",
                'data' => $user
            ];
        } catch (Exception $e) {

            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
    }

    public function updateUser(Request $request)
    {
        $updated_user = User::find($request->id);

        try {

            $updated_user->update([
                'profile_picture' => $request->profile_picture,
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'born_date' => $request->born_date
            ]);

            return [
                'status' => Response::HTTP_OK,
                'message' => "Edit successful !",
                'data' => $updated_user
            ];
        } catch (Exception $e) {
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
    }
}
