<?php
namespace App\Livewire\Admin;
use Livewire\Component;
use App\Models\AccessCode;
use App\Models\Plan;

class GestionCodes extends Component
{
    // ── État liste ──────────────────────────────────────────
    public string $filtreStatut = '';
    public string $filtrePlan   = '';
    public string $recherche    = '';

    // ── État formulaire génération ──────────────────────────
    public string $plan      = 'mensuel';
    public int    $quantite  = 1;
    public bool   $estPayant = false;

    // ── Codes générés ───────────────────────────────────────
    public array $derniersCodesGeneres = [];

    // ── Plans disponibles (pour le select + affichage prix) ─
    public array $plans = [];

    public function mount(): void
    {
        $this->plans = Plan::actifs()->get(['nom', 'slug', 'prix'])->toArray();
    }

    // ── Validation ──────────────────────────────────────────
    protected function rules(): array
    {
        $slugs = Plan::actifs()->pluck('slug')->implode(',');
        return [
            'plan'     => 'required|in:' . $slugs,
            'quantite' => 'required|integer|min:1|max:20',
            'estPayant' => 'boolean',
        ];
    }

    protected array $messages = [
        'quantite.max' => 'Maximum 20 codes à la fois.',
    ];

    // // ── Prix du plan sélectionné (computed) ─────────────────
    // public function getPlanActuelProperty(): ?array
    // {
    //     return collect($this->plans)->firstWhere('slug', $this->plan);
    // }

    // ── Générer codes ────────────────────────────────────────
    public function generer(): void
    {
        $this->validate();
        $this->derniersCodesGeneres = [];

        for ($i = 0; $i < $this->quantite; $i++) {
            $code = AccessCode::genererManuel($this->plan, auth()->user()->id, $this->estPayant);
            $this->derniersCodesGeneres[] = $code->code;
        }

        session()->flash('success', $this->quantite . ' code(s) généré(s) avec succès.');
    }

    // ── Révoquer ─────────────────────────────────────────────
    public function revoquer(int $id): void
    {
        $code = AccessCode::findOrFail($id);
        if ($code->statut === 'utilise') {
            session()->flash('error', 'Impossible de révoquer un code déjà utilisé.');
            return;
        }
        $code->update(['statut' => 'revoque']);
        session()->flash('success', 'Code révoqué.');
    }

    // ── Render ───────────────────────────────────────────────
    public function render()
    {
        $planActuel = collect($this->plans)->firstWhere('slug', $this->plan);

        $codes = AccessCode::with(['user', 'generePar'])
            ->when($this->filtreStatut, fn($q) => $q->where('statut', $this->filtreStatut))
            ->when($this->filtrePlan,   fn($q) => $q->where('plan', $this->filtrePlan))
            ->when($this->recherche,    fn($q) => $q->where('code', 'like', '%' . $this->recherche . '%')
                ->orWhereHas('user', fn($q) => $q->where('telephone', 'like', '%' . $this->recherche . '%')
                    ->orWhere('nom', 'like', '%' . $this->recherche . '%')
                )
            )
            ->latest()
            ->get();

        return view('livewire.admin.gestion-codes', compact('codes', 'planActuel'));
    }
}