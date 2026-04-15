<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Coupon;
use App\Models\CouponCode;
use App\Models\Selection;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GestionCoupons extends Component
{
    // -------------------------------------------------------
    // État liste
    // -------------------------------------------------------
    public string $filtreStatut = '';
    public string $filtreRisque = '';
    public string $recherche    = '';

    // -------------------------------------------------------
    // État modal formulaire
    // -------------------------------------------------------
    public bool $showModal     = false;
    public bool $showResultatModal = false;
    public bool $modeEdition   = false;
    public ?int $couponId      = null;

    // Champs coupon
    public string $titre             = '';
    public string $niveau_risque     = 'modere';
    public string $description       = '';
    public string $analyse           = '';

    // Codes bookmakers [bookmaker => code]
    public array $codes = [
        '1xbet'     => '',
        'betwinner' => '',
        'melbet'    => '',
        '1win'      => '',
    ];

    // Sélections
    public array $selections = [];

    // Modal résultat
    public ?int   $resultatCouponId = null;
    public string $statut_resultat  = 'en_cours';

    // Upload capture gagnant
    use WithFileUploads;
    public ?string $capture_gagnant_path = null;
    public $capture_gagnant = null;

    // État modal capture
    public bool $showCaptureModal = false;
    public ?int $captureGagnantId = null;
    public $capture_gagnant_upload = null; 

    // -------------------------------------------------------
    // Validation
    // -------------------------------------------------------
    protected function rules(): array
    {
        return [
            'titre'                     => 'required|min:3|max:255',
            'niveau_risque'             => 'required|in:faible,modere,risque',
            'description'               => 'nullable|max:1000',
            'analyse'                   => 'nullable|max:2000',
            'codes.1xbet'               => 'nullable|max:100',
            'codes.betwinner'           => 'nullable|max:100',
            'codes.melbet'              => 'nullable|max:100',
            'codes.1win'                => 'nullable|max:100',
            'selections.*.equipe_domicile'  => 'nullable|max:100',
            'selections.*.equipe_exterieur' => 'nullable|max:100',
            'selections.*.competition'      => 'nullable|max:100',
            'selections.*.date_match'       => 'nullable|date',
            'selections.*.type_pari'        => 'nullable|max:100',
            'selections.*.cote'             => 'nullable|numeric|min:1',
            'capture_gagnant' => 'nullable|image|max:2048',
            'capture_gagnant_upload' => 'nullable|image|max:2048',
        ];
    }

    protected array $messages = [
        'titre.required' => 'Le titre est obligatoire.',
        'titre.min'      => 'Le titre doit faire au moins 3 caractères.',
        'niveau_risque.required' => 'Le niveau de risque est obligatoire.',
    ];

    // -------------------------------------------------------
    // Lifecycle
    // -------------------------------------------------------
    public function updatingRecherche(): void
    {
        $this->resetPage();
    }

    // -------------------------------------------------------
    // Sélections dynamiques
    // -------------------------------------------------------
    public function ajouterSelection(): void
    {
        $this->selections[] = [
            'equipe_domicile'  => '',
            'equipe_exterieur' => '',
            'competition'      => '',
            'date_match'       => '',
            'type_pari'        => '',
            'cote'             => '',
        ];
    }

    public function supprimerSelection(int $index): void
    {
        array_splice($this->selections, $index, 1);
        $this->selections = array_values($this->selections);
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
        $coupon = Coupon::with(['codes', 'selections'])->findOrFail($id);

        $this->couponId      = $coupon->id;
        $this->titre         = $coupon->titre;
        $this->niveau_risque = $coupon->niveau_risque;
        $this->description   = $coupon->description ?? '';
        $this->analyse       = $coupon->analyse ?? '';
        $this->capture_gagnant_path = $coupon->capture_gagnant ?? null;
        $this->capture_gagnant = null;

        // Charger codes bookmakers
        $this->codes = [
            '1xbet'     => '',
            'betwinner' => '',
            'melbet'    => '',
            '1win'      => '',
        ];
        foreach ($coupon->codes as $code) {
            $key = strtolower($code->bookmaker);
            if (array_key_exists($key, $this->codes)) {
                $this->codes[$key] = $code->code;
            }
        }

        // Charger sélections
        $this->selections = $coupon->selections->map(fn($s) => [
            'equipe_domicile'  => $s->equipe_domicile  ?? '',
            'equipe_exterieur' => $s->equipe_exterieur ?? '',
            'competition'      => $s->competition      ?? '',
            'date_match'       => $s->date_match ? $s->date_match->format('Y-m-d\TH:i') : '',
            'type_pari'        => $s->type_pari        ?? '',
            'cote'             => $s->cote             ?? '',
        ])->toArray();

        $this->modeEdition = true;
        $this->showModal   = true;
    }

    // -------------------------------------------------------
    // Sauvegarder (création ou édition)
    // -------------------------------------------------------
    public function sauvegarder(): void
    {
        $this->validate();

        DB::transaction(function () {
            $data = [
                'titre'        => $this->titre,
                'niveau_risque'=> $this->niveau_risque,
                'description'  => $this->description ?: null,
                'analyse'      => $this->analyse ?: null,
                'cree_par'     => auth()->user()->id,
            ];

            // Ajout capture si uploadée
            if ($this->capture_gagnant && is_object($this->capture_gagnant)) {
                $data['capture_gagnant'] = $this->capture_gagnant->store('coupons', 'public');
            } elseif ($this->capture_gagnant_path) {
                $data['capture_gagnant'] = $this->capture_gagnant_path; // conserver l'existante
            }

            if ($this->modeEdition) {
                $coupon = Coupon::findOrFail($this->couponId);
                $coupon->update($data);
            } else {
                $coupon = Coupon::create(array_merge($data, [
                    'statut_publication' => 'depublie',
                    'statut_resultat'    => 'en_cours',
                ]));
            }

            // Codes bookmakers : supprimer puis recréer
            $coupon->codes()->delete();
            foreach ($this->codes as $bookmaker => $code) {
                if (!empty(trim($code))) {
                    CouponCode::create([
                        'coupon_id' => $coupon->id,
                        'bookmaker' => $bookmaker,
                        'code'      => trim($code),
                    ]);
                }
            }

            // Sélections : supprimer puis recréer
            $coupon->selections()->delete();
            foreach ($this->selections as $sel) {
                $lignesVides = empty($sel['equipe_domicile'])
                    && empty($sel['equipe_exterieur'])
                    && empty($sel['type_pari']);

                if (!$lignesVides) {
                    Selection::create([
                        'coupon_id'        => $coupon->id,
                        'equipe_domicile'  => $sel['equipe_domicile']  ?: null,
                        'equipe_exterieur' => $sel['equipe_exterieur'] ?: null,
                        'competition'      => $sel['competition']      ?: null,
                        'date_match'       => $sel['date_match']       ?: null,
                        'type_pari'        => $sel['type_pari']        ?: null,
                        'cote'             => $sel['cote']             ?: null,
                        'statut'           => 'en_cours',
                    ]);
                }
            }
        });

        $this->showModal = false;
        $this->resetForm();
        session()->flash('success', $this->modeEdition ? 'Coupon mis à jour.' : 'Coupon créé.');
    }

    // -------------------------------------------------------
    // Publier / Dépublier
    // -------------------------------------------------------
    public function publier(int $id): void
    {
        Coupon::findOrFail($id)->publier();
        session()->flash('success', 'Coupon publié.');
    }

    public function depublier(int $id): void
    {
        Coupon::findOrFail($id)->depublier();
        session()->flash('success', 'Coupon dépublié.');
    }

    // -------------------------------------------------------
    // Modal résultat
    // -------------------------------------------------------
    public function ouvrirResultat(int $id): void
    {
        $coupon = Coupon::findOrFail($id);
        $this->resultatCouponId = $id;
        $this->statut_resultat  = $coupon->statut_resultat ?? 'en_cours';
        $this->showResultatModal = true;
    }

    public function sauvegarderResultat(): void
    {
        $this->validate([
            'statut_resultat' => 'required|in:en_cours,gagne,perdu,annule',
        ]);

        Coupon::findOrFail($this->resultatCouponId)
            ->update(['statut_resultat' => $this->statut_resultat]);

        $this->showResultatModal = false;
        session()->flash('success', 'Résultat mis à jour.');
    }

    // -------------------------------------------------------
    // Supprimer
    // -------------------------------------------------------
    public function supprimer(int $id): void
    {
        Coupon::findOrFail($id)->delete();
        session()->flash('success', 'Coupon supprimé.');
    }

    public function ouvrirCapture(int $id): void
    {
        $this->captureGagnantId = $id;
        $this->capture_gagnant_upload = null;
        $this->showCaptureModal = true;
    }

    public function sauvegarderCapture(): void
    {
        $this->validate([
            'capture_gagnant_upload' => 'required|image|max:2048',
        ]);

        $coupon = Coupon::findOrFail($this->captureGagnantId);

        // Supprimer l'ancienne si elle existe
        if ($coupon->capture_gagnant) {
            Storage::disk('public')->delete($coupon->capture_gagnant);
        }

        $coupon->update([
            'capture_gagnant' => $this->capture_gagnant_upload->store('coupons', 'public'),
        ]);

        $this->showCaptureModal = false;
        $this->capture_gagnant_upload = null;
        session()->flash('success', 'Capture enregistrée.');
    }

    // -------------------------------------------------------
    // Reset formulaire
    // -------------------------------------------------------
    private function resetForm(): void
    {
        $this->couponId      = null;
        $this->titre         = '';
        $this->niveau_risque = 'modere';
        $this->description   = '';
        $this->analyse       = '';
        $this->codes         = [
            '1xbet'     => '',
            'betwinner' => '',
            'melbet'    => '',
            '1win'      => '',
        ];
        $this->selections    = [];
        $this->resetValidation();
        $this->capture_gagnant = null;
        $this->capture_gagnant_path = null;
    }

    private function resetPage(): void
    {
        // Placeholder si pagination Livewire ajoutée plus tard
    }

    // -------------------------------------------------------
    // Render
    // -------------------------------------------------------
    public function render()
    {
        $coupons = Coupon::with(['codes', 'selections', 'creePar'])
            ->when($this->filtreStatut, fn($q) => $q->where('statut_publication', $this->filtreStatut))
            ->when($this->filtreRisque, fn($q) => $q->where('niveau_risque', $this->filtreRisque))
            ->when($this->recherche,   fn($q) => $q->where('titre', 'like', '%' . $this->recherche . '%'))
            ->latest()
            ->get();

        return view('livewire.admin.gestion-coupons', compact('coupons'));
    }
}