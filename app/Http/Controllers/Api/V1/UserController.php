<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // Méthode pour enregistrer un utilisateur
    public function register(Request $request)
    {
        // Valider les données d'inscription
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'gender' => 'required|string|in:male,female',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Créer un nouvel utilisateur
        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
        ]);

        // Retourner la réponse en JSON avec les informations utilisateur
        return response()->json([
            'message' => 'User registered successfully!',
            'user' => $user
        ], 201);
    }
}
