<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\{Request, Response, JsonResponse};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class UserController extends Controller
{
    /**
     * register
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'unique:users,email', 'max:255', 'email'],
            'password' => ['required', 'min:4', 'max:8'],
        ]);

        if (!$validate->fails()) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            return response()->json([
                'status' => Response::HTTP_CREATED,
                'message' => 'Create Account Successfully!',
                'token' => $user->createToken('access')->plainTextToken
            ]);
        } else {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => $validate->getMessageBag()
            ]);
        }
    }

    /**
     * login
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'email' => ['required', 'max:255', 'email'],
            'password' => ['required', 'min:4', 'max:8'],
        ]);

        if (!$validate->fails()) {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'Invalid credentials!',
                ]);
            } else {
                return response()->json([
                    'status' => Response::HTTP_OK,
                    'message' => 'Logged in Successfully!',
                    'token' => $user->createToken('access')->plainTextToken
                ]);
            }
        } else {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => $validate->getMessageBag()
            ]);
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'Logged Out!'
        ]);
    }
}
