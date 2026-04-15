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
        ];
    }

    protected array $messages = [
        'nom.required'         => 'Le nom est obligatoire.',
        'slug.required'        => 'Le slug est obligatoire.',
        'slug.unique'          => 'Ce slug est déjà utilisé.',
        'prix.required'        => 'Le prix est obligatoire.',
        'duree_jours.required' => 'La durée est obligatoire.',
        'duree_jours.min'      => 'La durée doit être d\'au moins 1 jour.',
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
            'nom'         => $this->nom,
            'slug'        => $this->slug,
            'prix'        => $this->prix,
            'duree_jours' => $this->duree_jours,
            'actif'       => $this->actif,
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
        $this->planId      = null;
        $this->nom         = '';
        $this->slug        = '';
        $this->prix        = 0;
        $this->duree_jours = 30;
        $this->actif       = true;
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