<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserLoginController extends Controller
{
    protected function validateLogin(Request $request)
{
    // Validation des champs requis
    $request->validate([
        'email' => 'required|string|email', // Vérifiez que l'email est requis et au bon format
        'password' => 'required|string', // Vérifiez que le mot de passe est requis
        'g-recaptcha-response' => config('captcha.active') ? 'required|captcha' : '',
    ], [
        'g-recaptcha-response.required' => 'Please verify that you are not a robot.',
        'g-recaptcha-response.captcha' => 'Captcha error! try again later or contact site admin.',
    ]);

    // Authentification de l'utilisateur
    if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        return response()->json(['error' => 'Unauthorized'], 401); // Retourner une erreur si l'authentification échoue
    }

    // Générer un token pour l'utilisateur authentifié
    $user = Auth::user();
    $token = $user->createToken('YourAppName')->accessToken; // Remplacez 'YourAppName' par le nom de votre application

    // Retourner une réponse avec le message et le token
    return response()->json([
        'message' => 'Login successful',
        'token' => $token,
    ], 200);
}


}
