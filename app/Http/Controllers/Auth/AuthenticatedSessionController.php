<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $token = $request->user()->createToken("API TOKEN");

        return response()->json([
            'success' => true,
            'errors'  => [],
            'data'    => [
                'message' => 'User Created Successfully',
                'token'   => $token->plainTextToken,
            ]
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'errors'  => [],
            'data'    => [
                'message' => 'User logged out Successfully',
            ]
        ]);
    }
}
