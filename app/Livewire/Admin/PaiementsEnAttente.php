<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Paiement;

class PaiementsEnAttente extends Component
{
    // -------------------------------------------------------
    // État liste
    // -------------------------------------------------------
    public string $filtreStatut  = 'en_attente';
    public string $filtreOperateur = '';
    public string $recherche     = '';

    // -------------------------------------------------------
    // État modal rejet
    // -------------------------------------------------------
    public bool   $showRejetModal  = false;
    public ?int   $rejetPaiementId = null;
    public string $motifRejet      = '';

    // -------------------------------------------------------
    // État modal détail
    // -------------------------------------------------------
    public bool  $showDetailModal = false;
    public ?int  $detailPaiementId = null;

    // -------------------------------------------------------
    // Validation
    // -------------------------------------------------------
    protected function rules(): array
    {
        return [
            'motifRejet' => 'nullable|max:500',
        ];
    }

    // -------------------------------------------------------
    // Valider un paiement
    // -------------------------------------------------------
    public function valider(int $id): void
    {
        $paiement = Paiement::findOrFail($id);

        if (!$paiement->isEnAttente()) {
            session()->flash('error', 'Ce paiement a déjà été traité.');
            return;
        }

        $code = $paiement->valider(auth()->user());

        session()->flash('success', "Paiement validé. Code généré : {$code->code}");
    }

    // -------------------------------------------------------
    // Ouvrir modal rejet
    // -------------------------------------------------------
    public function ouvrirRejet(int $id): void
    {
        $this->rejetPaiementId = $id;
        $this->motifRejet      = '';
        $this->showRejetModal  = true;
        $this->resetValidation();
    }

    // -------------------------------------------------------
    // Confirmer rejet
    // -------------------------------------------------------
    public function confirmerRejet(): void
    {
        $this->validate();

        $paiement = Paiement::findOrFail($this->rejetPaiementId);

        if (!$paiement->isEnAttente()) {
            session()->flash('error', 'Ce paiement a déjà été traité.');
            $this->showRejetModal = false;
            return;
        }

        $paiement->rejeter(auth()->user(), $this->motifRejet ?: null);

        $this->showRejetModal = false;
        $this->motifRejet     = '';
        session()->flash('success', 'Paiement rejeté.');
    }

    // -------------------------------------------------------
    // Modal détail
    // -------------------------------------------------------
    public function voirDetail(int $id): void
    {
        $this->detailPaiementId = $id;
        $this->showDetailModal  = true;
    }

    // -------------------------------------------------------
    // Render
    // -------------------------------------------------------
    public function render()
    {
        $paiements = Paiement::with('user')
            ->when(
                $this->filtreStatut,
                fn($q) => $q->where('statut', $this->filtreStatut)
            )
            ->when(
                $this->filtreOperateur,
                fn($q) => $q->where('operateur', $this->filtreOperateur)
            )
            ->when(
                $this->recherche,
                fn($q) => $q->whereHas('user', function ($q) {
                    $q->where('telephone', 'like', '%' . $this->recherche . '%')
                      ->orWhere('nom', 'like', '%' . $this->recherche . '%');
                })
            )
            ->latest()
            ->get();

        $detailPaiement = $this->detailPaiementId
            ? Paiement::with('user')->find($this->detailPaiementId)
            : null;

        return view('livewire.admin.paiements-en-attente', compact('paiements', 'detailPaiement'));
    }
}