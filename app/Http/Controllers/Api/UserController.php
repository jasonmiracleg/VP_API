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
            // $file = $request->file('image');
            // $imageName = time() . '_' . $file->getClientOriginalName();
            // $file->move(\public_path("/assets/image/"), $imageName);

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->born_date = $request->born_date;
            // $user->image = $imageName;
            $user->save();

            return [
                'status' => Response::HTTP_OK,
                'message' => "Sign up successful !",
                'data' => $user->id
            ];
        } catch (Exception $e) {

            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
                'data' => 0
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

            // if (File::exists("assets/image/" . $updated_user->image)) {
            //     File::delete("assets/image/" . $updated_user->image);
            // }

            // $file = $request->file('image');
            // $imageName = time() . '_' . $file->getClientOriginalName();
            // $file->move(\public_path("/assets/image/"), $imageName);

            $updated_user->update([
                'name' => $request->name,
                // 'image' => $imageName,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'born_date' => $request->born_date,
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
