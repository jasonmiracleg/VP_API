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
        try {
            $user = User::where('email', $request->email)->first();

            if (Hash::check($request->password, $user->password)) {
                return [
                    'status' => Response::HTTP_OK,
                    'message' => "Sign In Successful",
                    'data' => $user->createToken('login')->plainTextToken
                ];
            }
        } catch (Exception $e) {
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
                'data' => []
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
