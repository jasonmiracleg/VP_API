<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationController extends Controller
{
    public function logIn(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!empty($user)) {
            if (Hash::check($request->password, $user->password)) {
                return [
                    'status' => Response::HTTP_OK,
                    'message' => "Sign In Successful",
                    'token' => $user->createToken('login')->plainTextToken,
                    'userId' => $user->id
                ];
            } else {
                return [
                    'status' => Response::HTTP_FORBIDDEN,
                    'message' => 'Incorrect Password',
                    'userId' => -1,
                    'token' => ""
                ];
            }
        } else {
            return [
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'User Not Found',
                'userId' => -1,
                'token' => ""
            ];
        }
    }

    public function logOut(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return [
            'status' => Response::HTTP_OK,
            'message' => 'Token Deleted',
            'data' => []
        ];
    }
}
