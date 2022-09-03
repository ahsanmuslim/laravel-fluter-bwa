<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Laravel\Fortify\Rules\Password;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    
    public function register(Request $request)
    {
        try {

            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'max:255', 'unique:users'],
                'email' => ['required', 'email', 'string', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'max:255', new Password]
            ]);

            //proses create user berdasrkan request yang sudah divalidasi
            User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password)
            ]);

            //ngambil data email dari database unutk cek apakah ada data double
            $user = User::where('email', $request->email)->first();

            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return ResponseFormatter::success(
                [
                    'access_token' => $tokenResult,
                    'token_type' => 'Bearer',
                    'user' => $user
                ],
                'User registered'
            );

        } catch (Exception $error) {

            return ResponseFormatter::error([
                'message' => 'Something went error',
                'error' => $error
            ], 'Authentication failed', 508);          
        
        }
    }

    public function login(Request $request)
    {
        try {

            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $credential = request(['email', 'password']);

            if(!Auth::attempt($credential)){
                return ResponseFormatter::success([
                    'messages' => 'Unauthorized'
                ], 'Authentication failed', 500);
            }

            $user = User::where('email', $request->email)->first();

            if(!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Invalid credential');
            }

            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Authenticated');

        } catch (Exception $error) {
            return ResponseFormatter::error([
                'messages' => 'Something wnet error',
                'error' => $error
            ], 'Authentication error', 500);
        }
    }

    public function fetch(Request $request)
    {
        return ResponseFormatter::success($request->user(), 'Data profile user berhasil diambil');
    }

    public function updateProfile(Request $request)
    {
        try {

            $data = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'phone' => ['required', 'string', 'max:255']
            ]);

            $user = Auth::user();
            $user->update($data);

            return ResponseFormatter::success($user, "Profile updated");


        } catch (Exception $err) {
            return ResponseFormatter::error([
                'messages' => 'Data tidak terupdate',
                'error' => $err
            ], 'Authentication error', 500);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken()->delete();

        return ResponseFormatter::success($token, 'Token Revoked');
    }

}
