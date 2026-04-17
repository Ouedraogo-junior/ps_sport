<?php

namespace App\Livewire\Admin;

use App\Models\DemandeRetrait;
use Livewire\Component;
use Livewire\WithPagination;

class GestionRetraits extends Component
{
    use WithPagination;

    public string $filtre = 'en_attente'; // en_attente | valide | rejete | tous
    public ?int $demandeIdSelectionnee = null;
    public ?DemandeRetrait $demandeSelectionnee = null;
    public string $noteAdmin = '';
    public bool $modalOuvert = false;
    public string $actionModal = ''; // valider | rejeter

    protected $queryString = ['filtre'];

    // Ouvre le modal de confirmation
    public function ouvrirModal(int $id, string $action): void
    {
        $this->demandeIdSelectionnee = $id;
        $this->demandeSelectionnee   = DemandeRetrait::with('user')->findOrFail($id);
        $this->actionModal           = $action;
        $this->noteAdmin             = '';
        $this->modalOuvert           = true;
    }

    public function fermerModal(): void
    {
        $this->modalOuvert           = false;
        $this->demandeIdSelectionnee = null;
        $this->demandeSelectionnee   = null;
        $this->noteAdmin             = '';
        $this->actionModal           = '';
    }

    // Valider le retrait
    public function valider(): void
    {
        $demande = DemandeRetrait::findOrFail($this->demandeIdSelectionnee);

        if ($demande->statut !== 'en_attente') {
            session()->flash('error', 'Cette demande a déjà été traitée.');
            $this->fermerModal();
            return;
        }

        // Vérifier que le solde est suffisant
        $solde = \App\Models\SoldeInvestissement::pourUser($demande->user_id);
        if ($solde->solde < $demande->montant) {
            session()->flash('error', 'Solde insuffisant pour cet utilisateur.');
            $this->fermerModal();
            return;
        }

        $demande->valider(auth()->user()->id);

        session()->flash('success', 'Retrait validé. Pensez à effectuer le virement Mobile Money.');
        $this->fermerModal();
    }

    // Rejeter le retrait
    public function rejeter(): void
    {
        $demande = DemandeRetrait::findOrFail($this->demandeIdSelectionnee);

        if ($demande->statut !== 'en_attente') {
            session()->flash('error', 'Cette demande a déjà été traitée.');
            $this->fermerModal();
            return;
        }

        $demande->rejeter(auth()->user()->id, $this->noteAdmin ?: null);

        session()->flash('success', 'Demande de retrait rejetée.');
        $this->fermerModal();
    }

    public function render()
    {
        $query = DemandeRetrait::with('user')
            ->orderBy('created_at', 'desc');

        if ($this->filtre !== 'tous') {
            $query->where('statut', $this->filtre);
        }

        $demandes = $query->paginate(15);

        $stats = [
            'en_attente' => DemandeRetrait::where('statut', 'en_attente')->count(),
            'valide'     => DemandeRetrait::where('statut', 'valide')->count(),
            'rejete'     => DemandeRetrait::where('statut', 'rejete')->count(),
        ];

        return view('livewire.admin.gestion-retraits', compact('demandes', 'stats'));
    }
}