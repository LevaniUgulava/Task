<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SendOtp;
use App\Http\Requests\SendVerificationRequest;

use App\Models\User;
use App\Notifications\ResetPasswordVerificationNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }


    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => 'user registered successfully!',
            'user' => $user,
        ]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }


    public function edit(EditUserRequest $request)
    {
        $user = auth()->user();
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return response()->json(['message' => 'User updated successfully'], 200);
    }

    public function forgetPassword(SendOtp $request)
    {
        $request->validated();
        $email = $request->email;
        $user = User::where('email', $email)->first();

        $user->notify(new ResetPasswordVerificationNotification());

        return response()->json([
            'success' => 'check email!'
        ]);
    }


    public function reset(ResetPasswordRequest $request)
    {
        $userEmail = auth()->user()->email;

        $otp = DB::table('otps')->where('identifier', $userEmail)->where('valid', true)->first();

        if ($otp) {
            if ($otp->token === $request->otp) {
                return response()->json([
                    'route' => route('edit'),
                ]);
            } else {
                return response()->json([
                    'error' => 'Invalid OTP. Please try again.'
                ], 422);
            }
        } else {
            return response()->json([
                'error' => 'No valid OTP found for your email.'
            ], 404);
        }
    }
}
