<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class Password extends Controller
{
    public function adminResetPassword(Request $request){
        $request->validate([
            'user_id' => 'required|integer|min:1',
        ]);

        $user_id = $request->input('user_id');

        // Récupérer l'utilisateur avec l'identifiant fourni
        $user = User::find($user_id);

        if (!$user) {
            // Rediriger avec un message d'erreur si l'utilisateur n'est pas trouvé
            return redirect()->back()->with('error', 'Utilisateur non trouvé.');
        }

        // Réinitialiser le mot de passe en utilisant l'adresse e-mail de l'utilisateur
        $user->password = Hash::make($user->email);
        $user->save();

        // Rediriger avec un message de succès
        return redirect()->back()->with('success', 'Mot de passe réinitialisé avec succès.');
    }
}
