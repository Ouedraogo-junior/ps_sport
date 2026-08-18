<?php

namespace App\Livewire\Admin;

use App\Models\AffiliateBonus;
use App\Models\Referral;
use App\Models\ReferralCode;
use App\Models\SoldeAffiliation;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class GestionAffiliation extends Component
{
    use WithPagination;

    public string $onglet = 'parrains'; // parrains | paliers | historique

    public string $recherche = '';

    // --- Formulaire palier ---
    public bool $modalPalier   = false;
    public ?int $palierEditId  = null;
    public int $palierSeuil    = 0;
    public float $palierMontant = 0;

    // --- Confirmation suppression ---
    public ?int $supprimerId    = null;
    public bool $modalSupprimer = false;

    protected function rules(): array
    {
        return [
            'palierSeuil'   => 'required|integer|min:1',
            'palierMontant' => 'required|numeric|min:0',
        ];
    }

    // -------------------------------------------------------
    // Paliers — CRUD
    // -------------------------------------------------------
    public function ouvrirModalPalier(?int $id = null): void
    {
        $this->palierEditId = $id;

        if ($id) {
            $palier              = AffiliateBonus::findOrFail($id);
            $this->palierSeuil   = $palier->seuil;
            $this->palierMontant = $palier->montant;
        } else {
            $this->palierSeuil   = 0;
            $this->palierMontant = 0;
        }

        $this->modalPalier = true;
    }

    public function sauvegarderPalier(): void
    {
        $this->validateOnly('palierSeuil');
        $this->validateOnly('palierMontant');

        $query = AffiliateBonus::where('type', 'palier')
                               ->where('seuil', $this->palierSeuil);
        if ($this->palierEditId) {
            $query->where('id', '!=', $this->palierEditId);
        }
        if ($query->exists()) {
            $this->addError('palierSeuil', 'Un palier avec ce seuil existe déjà.');
            return;
        }

        AffiliateBonus::updateOrCreate(
            ['id' => $this->palierEditId ?? 0],
            [
                'type'    => 'palier',
                'seuil'   => $this->palierSeuil,
                'montant' => $this->palierMontant,
                'actif'   => true,
            ]
        );

        $this->modalPalier = false;
        $this->resetValidation();
        session()->flash('success', 'Palier sauvegardé.');
    }

    public function toggleActifPalier(int $id): void
    {
        $palier = AffiliateBonus::findOrFail($id);
        $palier->update(['actif' => !$palier->actif]);
    }

    public function confirmerSuppression(int $id): void
    {
        $this->supprimerId    = $id;
        $this->modalSupprimer = true;
    }

    public function supprimerPalier(): void
    {
        AffiliateBonus::findOrFail($this->supprimerId)->delete();
        $this->modalSupprimer = false;
        $this->supprimerId    = null;
        session()->flash('success', 'Palier supprimé.');
    }

    // -------------------------------------------------------
    // Render
    // -------------------------------------------------------
    public function render()
    {
        $parrains = User::with(['referralCode', 'soldeAffiliation'])
            ->withCount([
                'filleuls as filleuls_count' => fn($q) => $q->where('statut', 'credite')
            ])
            ->having('filleuls_count', '>', 0)
            ->when($this->recherche, fn($q) =>
                $q->where('nom', 'like', "%{$this->recherche}%")
                  ->orWhere('telephone', 'like', "%{$this->recherche}%")
            )
            ->orderByDesc('filleuls_count')
            ->paginate(15);

        $parrains->each(function ($parrain) {
            if (!$parrain->soldeAffiliation) {
                $parrain->soldeAffiliation = SoldeAffiliation::firstOrCreate(
                    ['user_id' => $parrain->id],
                    ['solde' => 0, 'total_cumule' => 0, 'filleuls_depuis_retrait' => 0]
                );
            }
        });

        $paliers = AffiliateBonus::paliers()->orderBy('seuil')->get();

        $historique = Referral::with(['parrain', 'filleul'])
            ->when($this->recherche, fn($q) =>
                $q->whereHas('parrain', fn($q) =>
                    $q->where('nom', 'like', "%{$this->recherche}%")
                      ->orWhere('telephone', 'like', "%{$this->recherche}%")
                )
            )
            ->latest()
            ->paginate(15);

        $stats = [
            'total_parrains' => ReferralCode::where('actif', true)->count(),
            'total_filleuls' => Referral::where('statut', 'credite')->count(),
            'total_credite'  => SoldeAffiliation::sum('total_cumule'),
        ];

        return view('livewire.admin.gestion-affiliation', compact(
            'parrains', 'paliers', 'historique', 'stats'
        ));
    }
}