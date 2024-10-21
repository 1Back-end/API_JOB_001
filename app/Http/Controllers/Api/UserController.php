<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        // Valider les données reçues
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'gender' => 'required|string|in:male,female',
        ]);

        // Si la validation échoue, renvoyer un message d'erreur
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Créer un nouvel utilisateur
        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'candidate',
            'status' => true,
        ]);

        // Retourner la réponse avec les données de l'utilisateur créé
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user,
        ], 201);
    }
}
