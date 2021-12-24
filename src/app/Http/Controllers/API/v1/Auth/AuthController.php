<?php

namespace App\Http\Controllers\API\v1\Auth;

use App\Helpers\UserRefactory;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register New User
     * @method POST
     * @param Request $request
     */

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);
        $data["password"] = Hash::make($data["password"]);
        $user = resolve(UserRefactory::class)->create($data);
        $default_super_admin_email = config('permission.default_super_admin_email');
        $user->email = $default_super_admin_email ? $user->assignRole('Super Admin') : $user->assignRole('User');
        return response()->json([
            'message' => 'user created successfuly'
        ], Response::HTTP_CREATED);
    }

    public function user()
    {
        return response()->json([auth()->user(), Response::HTTP_OK]);
    }

    /**
     * Log in User
     * @method GET
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (auth()->attempt($request->only(['email', 'password']))) {
            return response()->json(auth()->user(), Response::HTTP_OK);
        }
        throw ValidationException::withMessages([
            'email' => 'incorrect email'
        ]);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'user has logged out'], Response::HTTP_OK);
    }
}
