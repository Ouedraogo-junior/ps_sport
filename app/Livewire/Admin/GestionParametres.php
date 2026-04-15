<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Parametre;

class GestionParametres extends Component
{
    public string $groupeActif = 'tous';
    public bool   $showModal   = false;
    public bool   $modeEdition = false;

    public string $cle          = '';
    public string $cleOriginale = '';
    public string $valeur       = '';
    public string $libelle      = '';
    public string $groupe       = '';
    public string $type         = 'text';

    public array $types = ['text', 'textarea', 'tel', 'url', 'email', 'number'];

    protected function rules(): array
    {
        return [
            'cle'     => 'required|string|max:100|regex:/^[a-z0-9_]+$/',
            'valeur'  => 'nullable|string|max:1000',
            'libelle' => 'required|string|max:150',
            'groupe'  => 'required|string|max:50|regex:/^[a-z0-9_]+$/',
            'type'    => 'required|in:text,textarea,tel,url,email,number',
        ];
    }

    protected array $messages = [
        'cle.regex'    => 'La clé : minuscules, chiffres et underscores uniquement.',
        'groupe.regex' => 'Le groupe : minuscules, chiffres et underscores uniquement.',
    ];

    public function ouvrir(string $cle = ''): void
    {
        $this->resetValidation();

        if ($cle) {
            $p = Parametre::findOrFail($cle);
            $this->cleOriginale = $cle;
            $this->cle          = $p->cle;
            $this->valeur       = $p->valeur ?? '';
            $this->libelle      = $p->libelle ?? '';
            $this->groupe       = $p->groupe ?? '';
            $this->type         = $p->type ?? 'text';
            $this->modeEdition  = true;
        } else {
            $this->cle = $this->cleOriginale = $this->valeur = $this->libelle = $this->groupe = '';
            $this->type        = 'text';
            $this->modeEdition = false;
        }

        $this->showModal = true;
    }

    public function sauvegarder(): void
    {
        $this->validate();

        if ($this->modeEdition) {
            $p = Parametre::findOrFail($this->cleOriginale);

            if ($this->cle !== $this->cleOriginale) {
                if (Parametre::where('cle', $this->cle)->exists()) {
                    $this->addError('cle', 'Cette clé existe déjà.');
                    return;
                }
                $p->delete();
                Parametre::create([
                    'cle'     => $this->cle,
                    'valeur'  => $this->valeur,
                    'libelle' => $this->libelle,
                    'groupe'  => $this->groupe,
                    'type'    => $this->type,
                ]);
            } else {
                $p->update([
                    'valeur'  => $this->valeur,
                    'libelle' => $this->libelle,
                    'groupe'  => $this->groupe,
                    'type'    => $this->type,
                ]);
            }
        } else {
            if (Parametre::where('cle', $this->cle)->exists()) {
                $this->addError('cle', 'Cette clé existe déjà.');
                return;
            }

            Parametre::create([
                'cle'     => $this->cle,
                'valeur'  => $this->valeur,
                'libelle' => $this->libelle,
                'groupe'  => $this->groupe,
                'type'    => $this->type,
            ]);
        }

        $this->showModal = false;
        session()->flash('success', 'Paramètre sauvegardé.');
    }


    public function updatedLibelle(string $val): void
    {
        if (! $this->modeEdition) {
            $this->cle = str($val)
                ->lower()
                ->replaceMatches('/[^a-z0-9\s_]/', '')
                ->replaceMatches('/\s+/', '_')
                ->trim('_')
                ->toString();
        }
    }

    public function supprimer(string $cle): void
    {
        Parametre::findOrFail($cle)->delete();
        session()->flash('success', 'Paramètre supprimé.');
    }

    public function render()
    {
        $query = Parametre::orderBy('groupe')->orderBy('libelle');

        if ($this->groupeActif !== 'tous') {
            $query->where('groupe', $this->groupeActif);
        }

        $groupes = Parametre::select('groupe')
            ->distinct()
            ->orderBy('groupe')
            ->pluck('groupe')
            ->toArray();

        return view('livewire.admin.gestion-parametres', [
            'parametres' => $query->get(),
            'groupes'    => $groupes,
        ]);
    }
}