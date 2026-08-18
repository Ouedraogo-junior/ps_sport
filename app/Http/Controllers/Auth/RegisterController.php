<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\ReferralCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'telephone' => [
                'required',
                'string',
                'max:20',
                'unique:users,telephone',
                'regex:/^(\+226|00226|226)?[0-9]{8}$/',
            ],
            'nom'          => ['nullable', 'string', 'max:100'],
            'password'     => ['required', 'string', 'min:6', 'confirmed'],
            'code_parrain' => ['nullable', 'string', 'exists:referral_codes,code'],
        ], [
            'telephone.required'  => 'Le numéro de téléphone est obligatoire.',
            'telephone.unique'    => 'Ce numéro est déjà utilisé.',
            'telephone.regex'     => 'Le numéro doit être un numéro burkinabè valide.',
            'password.required'   => 'Le mot de passe est obligatoire.',
            'password.min'        => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.confirmed'  => 'Les mots de passe ne correspondent pas.',
            'code_parrain.exists' => 'Ce code de parrainage est invalide.',
        ]);

        $telephone = preg_replace('/^(\+226|00226|226)/', '', $request->telephone);

        // Résoudre le parrain via le code
        $referredBy = null;
        if ($request->filled('code_parrain')) {
            $referralCode = ReferralCode::where('code', $request->code_parrain)
                                        ->where('actif', true)
                                        ->first();
            $referredBy = $referralCode?->user_id;
        }

        $user = User::create([
            'telephone'   => $telephone,
            'nom'         => $request->nom,
            'password'    => Hash::make($request->password),
            'role'        => 'user',
            'statut'      => 'actif',
            'referred_by' => $referredBy,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Compte créé avec succès. Souscrivez un abonnement pour accéder aux coupons.');
    }
}