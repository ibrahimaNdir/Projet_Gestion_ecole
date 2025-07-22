<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nom_utilisateur' => ['required', 'string', 'unique:users,nom_utilisateur'],
            'email'           => ['required', 'email', 'unique:users,email'],
            'mot_de_passe'    => ['required', 'min:6'],
            'role_id'         => ['required', 'exists:roles,id']
        ]);

        $user = User::create([
            'nom_utilisateur' => $request->nom_utilisateur,
            'email'           => $request->email,
            'mot_de_passe'    => bcrypt($request->mot_de_passe),
            'role_id'         => $request->role_id
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'        => ['required', 'email'],
            'mot_de_passe' => ['required']
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['mot_de_passe'], $user->mot_de_passe)) {
            return response()->json(['message' => 'Identifiants invalides'], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnecté avec succès']);
    }
}
