<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    /**
     * Afficher le formulaire de modification du mot de passe
     * pour l'utilisateur actuellement connecté.
     */
    public function edit()
    {
        return view('mot-de-passe');
    }

    /**
     * Mettre à jour le mot de passe de l'utilisateur connecté.
     * Contrairement à la réinitialisation admin (mot de passe généré
     * aléatoirement), ici l'utilisateur choisit lui-même son nouveau
     * mot de passe après avoir confirmé l'ancien.
     */
    public function update(Request $request)
    {
        $request->validate([
            'mot_de_passe_actuel' => ['required', 'current_password'],
            'password'            => ['required', 'string', 'min:6', 'confirmed', 'different:mot_de_passe_actuel'],
        ], [
            'mot_de_passe_actuel.required'         => 'Veuillez saisir votre mot de passe actuel.',
            'mot_de_passe_actuel.current_password' => 'Le mot de passe actuel est incorrect.',
            'password.required'                    => 'Le nouveau mot de passe est obligatoire.',
            'password.min'                          => 'Le nouveau mot de passe doit contenir au moins 6 caractères.',
            'password.confirmed'                   => 'Les deux mots de passe ne correspondent pas.',
            'password.different'                   => 'Le nouveau mot de passe doit être différent de l\'ancien.',
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Votre mot de passe a été modifié avec succès.');
    }
}