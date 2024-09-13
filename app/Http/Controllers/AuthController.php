<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
//     public function __construct()
// {
// }
    // API login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        // $credentials = $request->only('email', 'password');

        $user = User::where('email', $request->email)->first();

        if (!$user || !\Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

            $token = $user->createToken('API Token')->plainTextToken;
            return response()->json(['token' => $token], 200);
    }

    // API logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    // Get the authenticated user
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
