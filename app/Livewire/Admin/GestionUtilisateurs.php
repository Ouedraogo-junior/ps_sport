<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Abonnement;

class GestionUtilisateurs extends Component
{
    // -------------------------------------------------------
    // État liste
    // -------------------------------------------------------
    public string $filtreStatut = '';
    public string $recherche    = '';

    // -------------------------------------------------------
    // État modal détail
    // -------------------------------------------------------
    public bool $showDetailModal = false;
    public ?int $detailUserId    = null;

    // -------------------------------------------------------
    // État modal réinitialisation
    // -------------------------------------------------------
    public bool $showResetModal      = false;
    public ?int $resetUserId         = null;
    public string $nouveauMotDePasse = '';
    public bool $motDePasseGenere    = false;


    // -------------------------------------------------------
    // Bloquer / Débloquer
    // -------------------------------------------------------
    public function bloquer(int $id): void
    {
        $user = User::findOrFail($id);

        if ($user->isAdmin()) {
            session()->flash('error', 'Impossible de bloquer un administrateur.');
            return;
        }

        $user->update(['statut' => 'bloque']);
        session()->flash('success', "Compte de {$user->nom} bloqué.");
    }

    public function debloquer(int $id): void
    {
        User::findOrFail($id)->update(['statut' => 'actif']);
        session()->flash('success', 'Compte débloqué.');
    }

    // -------------------------------------------------------
    // Modal détail
    // -------------------------------------------------------
    public function voirDetail(int $id): void
    {
        $this->detailUserId   = $id;
        $this->showDetailModal = true;
    }

    // -------------------------------------------------------
    // Modal réinitialisation
    // -------------------------------------------------------
    public function ouvrirReset(int $id): void
    {
        $this->resetUserId        = $id;
        $this->nouveauMotDePasse  = '';
        $this->motDePasseGenere   = false;
        $this->showResetModal     = true;
    }

    public function resetMotDePasse(): void
    {
        $mdp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        User::findOrFail($this->resetUserId)->update([
            'password' => bcrypt($mdp),
        ]);

        $this->nouveauMotDePasse = $mdp;
        $this->motDePasseGenere  = true;
    }

    // -------------------------------------------------------
    // Render
    // -------------------------------------------------------
    public function render()
    {
        $utilisateurs = User::where('role', 'user')
            ->when($this->filtreStatut, fn($q) => $q->where('statut', $this->filtreStatut))
            ->when($this->recherche, fn($q) => $q
                ->where('telephone', 'like', '%' . $this->recherche . '%')
                ->orWhere('nom', 'like', '%' . $this->recherche . '%')
            )
            ->withCount('paiements')
            ->with('abonnementActif')
            ->latest()
            ->get();

        $detailUser = $this->detailUserId
            ? User::with([
                'abonnementActif',
                'abonnements' => fn($q) => $q->latest()->take(5),
                'paiements'   => fn($q) => $q->latest()->take(5),
                'accessCodes' => fn($q) => $q->latest()->take(5),
            ])->find($this->detailUserId)
            : null;

        return view('livewire.admin.gestion-utilisateurs', compact('utilisateurs', 'detailUser'));
    }
}