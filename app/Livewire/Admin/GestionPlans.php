<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Plan;

class GestionPlans extends Component
{
    // -------------------------------------------------------
    // État modal
    // -------------------------------------------------------
    public bool  $showModal  = false;
    public bool  $modeEdition = false;
    public ?int  $planId     = null;

    // Champs
    public string $nom         = '';
    public string $slug        = '';
    public int    $prix        = 0;
    public int    $duree_jours = 30;
    public bool   $actif       = true;
    public bool   $est_investissement = false;
    public string $taux_journalier    = '';
    public string $seuil_retrait      = '';

    // -------------------------------------------------------
    // Validation
    // -------------------------------------------------------
    protected function rules(): array
    {
        $slugRule = $this->modeEdition
            ? 'required|unique:plans,slug,' . $this->planId
            : 'required|unique:plans,slug';

        return [
            'nom'         => 'required|min:2|max:100',
            'slug'        => $slugRule,
            'prix'        => 'required|integer|min:0',
            'duree_jours' => 'required|integer|min:1',
            'actif'       => 'boolean',
            'est_investissement'=> 'boolean',
            'taux_journalier'   => $this->est_investissement ? 'required|numeric|min:0.01|max:100' : 'nullable',
            'seuil_retrait'     => $this->est_investissement ? 'required|integer|min:1' : 'nullable',
        ];
    }

    protected array $messages = [
        'nom.required'         => 'Le nom est obligatoire.',
        'slug.required'        => 'Le slug est obligatoire.',
        'slug.unique'          => 'Ce slug est déjà utilisé.',
        'prix.required'        => 'Le prix est obligatoire.',
        'duree_jours.required' => 'La durée est obligatoire.',
        'duree_jours.min'      => 'La durée doit être d\'au moins 1 jour.',
        'taux_journalier.required' => 'Le taux journalier est obligatoire pour un plan investissement.',
        'taux_journalier.numeric'  => 'Le taux doit être un nombre.',
        'seuil_retrait.required'   => 'Le seuil de retrait est obligatoire pour un plan investissement.',
        'seuil_retrait.min'        => 'Le seuil doit être supérieur à 0.',
    ];

    // -------------------------------------------------------
    // Auto-slug depuis le nom
    // -------------------------------------------------------
    public function updatedNom(string $value): void
    {
        if (!$this->modeEdition) {
            $this->slug = \Illuminate\Support\Str::slug($value, '_');
        }
    }

    // -------------------------------------------------------
    // Ouvrir modal création
    // -------------------------------------------------------
    public function ouvrir(): void
    {
        $this->resetForm();
        $this->modeEdition = false;
        $this->showModal   = true;
    }

    // -------------------------------------------------------
    // Ouvrir modal édition
    // -------------------------------------------------------
    public function editer(int $id): void
    {
        $plan = Plan::findOrFail($id);

        $this->planId      = $plan->id;
        $this->nom         = $plan->nom;
        $this->slug        = $plan->slug;
        $this->prix        = $plan->prix;
        $this->duree_jours = $plan->duree_jours;
        $this->actif       = $plan->actif;
        $this->est_investissement= (bool) $plan->est_investissement;
        $this->taux_journalier   = $plan->taux_journalier ?? '';
        $this->seuil_retrait     = $plan->seuil_retrait ?? '';

        $this->modeEdition = true;
        $this->showModal   = true;
    }

    // -------------------------------------------------------
    // Sauvegarder
    // -------------------------------------------------------
    public function sauvegarder(): void
    {
        $this->validate();

        $data = [
            'nom'                => $this->nom,
            'slug'               => $this->slug,
            'prix'               => $this->prix,
            'duree_jours'        => $this->duree_jours,
            'actif'              => $this->actif,
            'est_investissement' => $this->est_investissement,
            'taux_journalier'    => $this->est_investissement ? $this->taux_journalier : null,
            'seuil_retrait'      => $this->est_investissement ? $this->seuil_retrait : null,
        ];

        if ($this->modeEdition) {
            Plan::findOrFail($this->planId)->update($data);
            session()->flash('success', 'Plan mis à jour.');
        } else {
            Plan::create($data);
            session()->flash('success', 'Plan créé.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    // -------------------------------------------------------
    // Activer / Désactiver
    // -------------------------------------------------------
    public function toggleActif(int $id): void
    {
        $plan = Plan::findOrFail($id);
        $plan->update(['actif' => !$plan->actif]);
        session()->flash('success', $plan->actif ? 'Plan désactivé.' : 'Plan activé.');
    }

    // -------------------------------------------------------
    // Supprimer
    // -------------------------------------------------------
    public function supprimer(int $id): void
    {
        Plan::findOrFail($id)->delete();
        session()->flash('success', 'Plan supprimé.');
    }

    // -------------------------------------------------------
    // Reset
    // -------------------------------------------------------
    private function resetForm(): void
    {
        $this->planId            = null;
        $this->nom               = '';
        $this->slug              = '';
        $this->prix              = 0;
        $this->duree_jours       = 30;
        $this->actif             = true;
        $this->est_investissement= false;
        $this->taux_journalier   = '';
        $this->seuil_retrait     = '';
        $this->resetValidation();
    }

    // -------------------------------------------------------
    // Render
    // -------------------------------------------------------
    public function render()
    {
        $plans = Plan::orderBy('prix')->get();
        return view('livewire.admin.gestion-plans', compact('plans'));
    }
}