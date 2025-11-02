<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
           'name' => 'required|string|max:255',
           'email' => 'required|string|email|max:255|unique:users',
           'password' => 'required|string|min:8',
           'iam_a' => 'required|in:student,teacher',
       ], [
           'name.required' => 'Nama harus diisi.',
           'name.string' => 'Nama harus berupa string.',
           'name.max' => 'Nama maksimal 255 karakter.',
           'email.required' => 'Email harus diisi.',
           'email.string' => 'Email harus berupa string.',
           'email.email' => 'Email tidak valid.',
           'email.unique' => 'Email sudah terdaftar.',
           'password.required' => 'Password harus diisi.',
           'password.string' => 'Password harus berupa string.',
           'password.min' => 'Password minimal 8 karakter.',
           'iam_a.required' => 'Role harus diisi.',
           'iam_a.in' => 'Role tidak valid.',
       ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'iam_a' => $request->iam_a
        ]);
        $user->assignRole($request->iam_a);
        $token = auth()->login($user);
        return response()->json([
            'success' => true,
            'message' => 'User berhasil didaftarkan !',
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]
        ]);
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'messages' => 'Email atau password tidak sesuai',
                'data' => []
            ],401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json([
            'success' => true,
            'message' => 'Berhasil login !',
            'data' => auth()->user()
        ]);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'success' => true,
            'message' => 'Berhasil login !',
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]
        ]);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil logout !',
            'data' => []
        ]);
    }
}
