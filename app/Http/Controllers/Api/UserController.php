<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

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
            $file = $request->file('image');
            $imageName = time() . '_' . $file->getClientOriginalName();
            $file->move(\public_path("/assets/image/"), $imageName);

            $user = User::create([
                'image' => $imageName,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'born_date' => $request->born_date,
                'productive_time' => '00:00:00'
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
        if (!$updated_user) {
            return [
                'message' => 'error'
            ];
        }


        try {

            $updated_user = User::find($request->id);

            if (File::exists("assets/image/" . $updated_user->image)) {
                File::delete("assets/image/" . $updated_user->image);
            }

            $file = $request->file('image');
            $imageName = time() . '_' . $file->getClientOriginalName();
            $file->move(\public_path("/assets/image/"), $imageName);

            $updated_user->update([
                'name' => $request->name,
                'image' => $imageName,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'born_date' => $request->born_date,
                'productive_time' => $request->productive_time
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
