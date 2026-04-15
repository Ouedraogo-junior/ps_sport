<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccessCode;
use App\Models\Paiement;
use App\Models\Plan;
use App\Models\Parametre;

class DashboardController extends Controller
{
    public function index()
    {
        $user            = auth()->user();
        $abonnementActif = $user->abonnementActif;
        $historique      = $user->abonnements()
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();
        $plans           = Plan::actifs()->orderBy('prix')->get();
        $dernierPaiement = $user->paiements()->latest()->first();

        $ussdOrange = Parametre::get('ussd_orange', '*144#');
        $ussdMoov   = Parametre::get('ussd_moov', '*555#');
        $numero  = Parametre::get('whatsapp_numero', '22600000000');
        $message = Parametre::get('whatsapp_message', 'Bonjour, je viens de faire mon paiement pour un abonnement.');
        $whatsappUrl = 'https://wa.me/' . $numero . '?text=' . urlencode($message);



        return view('dashboard', compact(
            'user',
            'abonnementActif',
            'historique',
            'plans',
            'dernierPaiement',
            'whatsappUrl',
            'ussdOrange',
            'ussdMoov',
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
}