<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccessCode;
use App\Models\Paiement;
use App\Models\Plan;
use App\Models\Parametre;
use App\Models\SoldeInvestissement;
use App\Models\DemandeRetrait;

class DashboardController extends Controller
{
    public function index()
    {
        $user            = auth()->user();
        $abonnementActif = $user->abonnementActif;
        $historique      = $user->abonnements()->orderBy('created_at', 'desc')->take(5)->get();
        $plans           = Plan::actifs()->orderBy('prix')->get();
        $dernierPaiement = $user->paiements()->latest()->first();

        $ussdOrange  = Parametre::get('ussd_orange', '*144#');
        $ussdMoov    = Parametre::get('ussd_moov', '*555#');
        $numero      = Parametre::get('whatsapp_numero', '22600000000');
        $message     = Parametre::get('whatsapp_message', 'Bonjour, je viens de faire mon paiement pour un abonnement.');
        $whatsappUrl = 'https://wa.me/' . $numero . '?text=' . urlencode($message);

        // Investissement
        $solde              = SoldeInvestissement::pourUser($user->id);
        $demandeEnAttente   = DemandeRetrait::where('user_id', $user->id)->where('statut', 'en_attente')->first();
        $derniersRetraits   = DemandeRetrait::where('user_id', $user->id)->latest()->take(5)->get();

        // Plan actif est-il investissement ?
        $planActif = $abonnementActif
            ? Plan::where('slug', $abonnementActif->plan)->first()
            : null;
        $estInvestisseur = $planActif?->est_investissement ?? false;
        $seuilRetrait    = $planActif?->seuil_retrait ?? null;

        return view('dashboard', compact(
            'user', 'abonnementActif', 'historique', 'plans', 'dernierPaiement',
            'whatsappUrl', 'ussdOrange', 'ussdMoov',
            'solde', 'demandeEnAttente', 'derniersRetraits', 'estInvestisseur', 'seuilRetrait'
        ));
    }

    // -------------------------------------------------------
    // Option A — Activer un code reçu via WhatsApp
    // -------------------------------------------------------
    public function activerCode(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:20'],
        ], [
            'code.required' => 'Veuillez saisir votre code d\'accès.',
        ]);

        $code = strtoupper(trim($request->code));

        $accessCode = AccessCode::where('code', $code)
            ->where(function ($q) {
                $q->whereNull('user_id')
                  ->orWhere('user_id', auth()->user()->id);
            })
            ->first();

        if (!$accessCode) {
            return back()->withErrors([
                'code' => 'Ce code est invalide ou ne correspond pas à votre compte.',
            ])->withInput();
        }

        if ($accessCode->statut === 'utilise') {
            return back()->withErrors([
                'code' => 'Ce code a déjà été utilisé.',
            ])->withInput();
        }

        if ($accessCode->statut === 'revoque') {
            return back()->withErrors([
                'code' => 'Ce code a été révoqué. Contactez-nous via WhatsApp.',
            ])->withInput();
        }

        if (!$accessCode->isValide()) {
            return back()->withErrors([
                'code' => 'Ce code a expiré. Contactez-nous via WhatsApp pour en obtenir un nouveau.',
            ])->withInput();
        }

        $abonnement = $accessCode->activer(auth()->user()->id);

        return redirect()->route('dashboard')
            ->with('success', "Abonnement activé ! Accès valide jusqu'au " . $abonnement->date_fin->format('d/m/Y') . '.');
    }

    // -------------------------------------------------------
    // Option B — Soumettre un paiement avec capture
    // -------------------------------------------------------
    public function soumettrePaiement(Request $request)
    {
        $request->validate([
            'plan'      => ['required', 'exists:plans,slug'],
            'operateur' => ['required', 'in:orange,moov'],
            'capture'   => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:3048'],
        ], [
            'plan.required'      => 'Veuillez choisir un plan.',
            'plan.exists'        => 'Plan invalide.',
            'operateur.required' => 'Veuillez choisir votre opérateur.',
            'capture.required'   => 'La capture d\'écran est obligatoire.',
            'capture.image'      => 'Le fichier doit être une image.',
            'capture.mimes'      => 'Formats acceptés : jpg, jpeg, png.',
            'capture.max'        => 'La capture ne doit pas dépasser 3 Mo.',
        ]);

        // Vérifier qu'il n'a pas déjà un paiement en attente
        $dejaEnAttente = auth()->user()->paiements()
            ->where('statut', 'en_attente')
            ->exists();

        if ($dejaEnAttente) {
            return back()->with('error', 'Vous avez déjà un paiement en attente de validation.');
        }

        // Récupérer le montant depuis le plan
        $plan   = Plan::where('slug', $request->plan)->firstOrFail();
        $path   = $request->file('capture')->store('captures', 'public');

        Paiement::create([
            'user_id'   => auth()->user()->id,
            'plan'      => $plan->slug,
            'montant'   => $plan->prix,
            'operateur' => $request->operateur,
            'statut'    => 'en_attente',
            'capture_path' => $path,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Paiement soumis ! Votre demande est en cours de validation. Vous recevrez votre code via WhatsApp sous 24h.');
    }


    public function demanderRetrait(Request $request)
    {
        $user  = auth()->user();
        $solde = SoldeInvestissement::pourUser($user->id);

        $planActif = $user->abonnementActif
            ? Plan::where('slug', $user->abonnementActif->plan)->first()
            : null;

        $seuilRetrait = $planActif?->seuil_retrait;

        // Vérifications
        if (!$planActif?->est_investissement) {
            return back()->with('error', 'Votre plan ne permet pas les retraits.');
        }

        if ($seuilRetrait && $solde->solde < $seuilRetrait) {
            return back()->with('error', 'Solde insuffisant. Minimum requis : ' . number_format($seuilRetrait, 0, ',', ' ') . ' FCFA.');
        }

        $dejaEnAttente = DemandeRetrait::where('user_id', $user->id)->where('statut', 'en_attente')->exists();
        if ($dejaEnAttente) {
            return back()->with('error', 'Vous avez déjà une demande de retrait en attente.');
        }

        $request->validate([
            'montant'          => ['required', 'numeric', 'min:' . ($seuilRetrait ?? 1), 'max:' . $solde->solde],
            'operateur'        => ['required', 'in:orange,moov,wave'],
            'numero_telephone' => ['required', 'string', 'max:20'],
        ], [
            'montant.min'  => 'Montant minimum : ' . number_format($seuilRetrait ?? 1, 0, ',', ' ') . ' FCFA.',
            'montant.max'  => 'Montant supérieur à votre solde disponible.',
        ]);

        DemandeRetrait::create([
            'user_id'          => $user->id,
            'montant'          => $request->montant,
            'operateur'        => $request->operateur,
            'numero_telephone' => $request->numero_telephone,
            'statut'           => 'en_attente',
        ]);

        return redirect()->route('dashboard')->with('success', 'Demande de retrait soumise. Traitement sous 24h.');
    }
    

    public function upgradePlan()
    {
        $user        = auth()->user();
        $abonnementActif = $user->abonnementActif;

        if (!$abonnementActif) {
            return redirect()->route('dashboard');
        }

        $planActif = Plan::where('slug', $abonnementActif->plan)->first();

        if (!$planActif?->est_investissement) {
            return redirect()->route('dashboard');
        }

        $plansSupérieurs = Plan::actifs()
            ->where('est_investissement', true)
            ->where('prix', '>', $planActif->prix)
            ->orderBy('prix')
            ->get();

        $ussdOrange  = Parametre::get('ussd_orange', '*144#');
        $ussdMoov    = Parametre::get('ussd_moov', '*555#');
        $numero      = Parametre::get('whatsapp_numero', '22600000000');
        $message     = Parametre::get('whatsapp_message', 'Bonjour, je viens de faire mon paiement pour un upgrade de plan.');
        $whatsappUrl = 'https://wa.me/' . $numero . '?text=' . urlencode($message);

        $dernierPaiement = $user->paiements()->latest()->first();

        return view('upgrade-plan', compact(
            'planActif', 'plansSupérieurs', 'ussdOrange', 'ussdMoov', 'whatsappUrl', 'dernierPaiement'
        ));
    }
    
}