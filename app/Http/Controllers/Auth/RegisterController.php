<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\Parametre;
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
        // Nettoyage : retirer espaces/tirets, puis l'indicatif Burkina Faso s'il est présent
        // (pour rester cohérent avec les comptes existants, stockés sans indicatif).
        // Les numéros d'autres pays sont conservés tels quels, indicatif inclus.
        // Fait AVANT la validation pour que le contrôle d'unicité porte sur la même
        // valeur que celle réellement stockée en base.
        $telephone = preg_replace('/[\s\-]/', '', (string) $request->telephone);
        $telephone = preg_replace('/^(\+226|00226|226)/', '', $telephone);
        $request->merge(['telephone' => $telephone]);

        $request->validate([
            'telephone' => [
                'required',
                'string',
                'max:20',
                'unique:users,telephone',
                'regex:/^\+?[0-9]{6,15}$/',
            ],
            'nom'          => ['nullable', 'string', 'max:100'],
            'password'     => ['required', 'string', 'min:6', 'confirmed'],
            'code_parrain' => ['nullable', 'string', 'exists:referral_codes,code'],
        ], [
            'telephone.required'  => 'Le numéro de téléphone est obligatoire.',
            'telephone.unique'    => 'Ce numéro est déjà utilisé.',
            'telephone.regex'     => 'Veuillez saisir un numéro de téléphone valide (6 à 15 chiffres, indicatif optionnel).',
            'password.required'   => 'Le mot de passe est obligatoire.',
            'password.min'        => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.confirmed'  => 'Les mots de passe ne correspondent pas.',
            'code_parrain.exists' => 'Ce code de parrainage est invalide.',
        ]);

        // Résoudre le parrain via le code
        $referredBy = null;
        if ($request->filled('code_parrain')) {
            $referralCode = ReferralCode::where('code', $request->code_parrain)
                                        ->where('actif', true)
                                        ->first();
            $referredBy = $referralCode?->user_id;
        }

        $user = User::create([
            'telephone'   => $request->telephone,
            'nom'         => $request->nom,
            'password'    => Hash::make($request->password),
            'role'        => 'user',
            'statut'      => 'actif',
            'referred_by' => $referredBy,
        ]);

        Auth::login($user);

        return redirect()->route('register.bienvenue');
    }

    /**
     * Page intermédiaire affichée juste après l'inscription : explique pourquoi
     * l'utilisateur est redirigé vers le canal Telegram, puis l'y envoie
     * automatiquement après un court délai (avec un bouton pour y aller
     * immédiatement).
     */
    public function bienvenue()
    {
        $telegramUrl = Parametre::get('telegram_canal_url');

        // Repli si le lien Telegram n'a pas encore été configuré par l'admin
        if (! $telegramUrl) {
            return redirect()->route('dashboard')
                ->with('success', 'Compte créé avec succès. Souscrivez un abonnement pour accéder aux coupons.');
        }

        return view('auth.bienvenue-telegram', [
            'telegramUrl' => $telegramUrl,
        ]);
    }
}