<div>

    {{-- ── BARRE D'OUTILS ─────────────────────────────────── --}}
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; gap:1rem; flex-wrap:wrap;">
        <div style="font-family:var(--font-display); font-size:0.82rem; letter-spacing:0.06em; text-transform:uppercase; color:var(--c-muted);">
            {{ $plans->count() }} plan(s)
        </div>
        <button wire:click="ouvrir" class="btn-sm-green">+ Nouveau plan</button>
    </div>

    {{-- ── TABLEAU desktop ─────────────────────────────────── --}}
    <div class="plans-table-wrap" style="background:var(--c-bg2); border:1px solid var(--c-border); overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Slug</th>
                    <th>Prix</th>
                    <th>Durée</th>
                    <th>Investissement</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($plans as $plan)
                    <tr>
                        <td style="font-family:var(--font-display); font-weight:700;">{{ $plan->nom }}</td>
                        <td style="font-family:monospace; color:var(--c-muted); font-size:0.85rem;">{{ $plan->slug }}</td>
                        <td style="font-family:var(--font-display); font-weight:700; color:var(--c-green);">{{ $plan->prixFormate() }}</td>
                        <td style="color:var(--c-muted);">{{ $plan->duree_jours }} jours</td>
                        <td>
                            @if($plan->est_investissement)
                                <span class="pill pill-green">{{ $plan->taux_journalier }}%/j</span>
                                <div style="font-size:0.75rem; color:var(--c-muted); margin-top:3px;">
                                    Seuil : {{ number_format($plan->seuil_retrait, 0, ',', ' ') }} XOF
                                </div>
                            @else
                                <span class="pill pill-gray">Non</span>
                            @endif
                        </td>
                        <td>
                            <span class="pill {{ $plan->actif ? 'pill-green' : 'pill-gray' }}">
                                {{ $plan->actif ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td>
                            <div style="display:flex; gap:6px; flex-wrap:wrap;">
                                <button wire:click="editer({{ $plan->id }})" class="btn-sm-outline">Éditer</button>
                                <button wire:click="toggleActif({{ $plan->id }})"
                                        class="btn-sm-outline"
                                        style="{{ $plan->actif ? 'color:var(--c-warning); border-color:rgba(255,171,0,0.3)' : 'color:var(--c-green); border-color:rgba(0,230,118,0.3)' }}">
                                    {{ $plan->actif ? 'Désactiver' : 'Activer' }}
                                </button>
                                <button wire:click="supprimer({{ $plan->id }})"
                                        wire:confirm="Supprimer ce plan ?"
                                        class="btn-sm-red">✕</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:3rem; color:var(--c-muted); font-family:var(--font-display); letter-spacing:0.06em; text-transform:uppercase; font-size:0.85rem;">
                            Aucun plan configuré
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── CARTES MOBILES ───────────────────────────────────── --}}
    <div class="plans-cards-wrap">
        @forelse($plans as $plan)
            <div style="background:var(--c-bg2); border:1px solid var(--c-border); padding:1rem; margin-bottom:0.75rem;">

                {{-- Ligne 1 : nom + statut --}}
                <div style="display:flex; align-items:center; justify-content:space-between; gap:0.5rem; margin-bottom:0.6rem;">
                    <span style="font-family:var(--font-display); font-weight:700; font-size:1rem;">{{ $plan->nom }}</span>
                    <span class="pill {{ $plan->actif ? 'pill-green' : 'pill-gray' }}">
                        {{ $plan->actif ? 'Actif' : 'Inactif' }}
                    </span>
                </div>

                {{-- Ligne 2 : slug + prix + durée --}}
                <div style="display:flex; gap:1rem; flex-wrap:wrap; margin-bottom:0.75rem;">
                    <div>
                        <div style="font-family:var(--font-display); font-size:0.65rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:2px;">Slug</div>
                        <span style="font-family:monospace; color:var(--c-muted); font-size:0.85rem;">{{ $plan->slug }}</span>
                    </div>
                    <div>
                        <div style="font-family:var(--font-display); font-size:0.65rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:2px;">Prix</div>
                        <span style="font-family:var(--font-display); font-weight:700; color:var(--c-green);">{{ $plan->prixFormate() }}</span>
                    </div>
                    <div>
                        <div style="font-family:var(--font-display); font-size:0.65rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:2px;">Durée</div>
                        <span style="color:var(--c-muted); font-size:0.85rem;">{{ $plan->duree_jours }} jours</span>
                    </div>
                    <div>
                        <div style="font-family:var(--font-display); font-size:0.65rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:2px;">Investissement</div>
                        @if($plan->est_investissement)
                            <span class="pill pill-green" style="font-size:0.75rem;">{{ $plan->taux_journalier }}%/j</span>
                        @else
                            <span style="color:var(--c-muted); font-size:0.85rem;">Non</span>
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                <div style="display:flex; gap:0.5rem;">
                    <button wire:click="editer({{ $plan->id }})" class="btn-sm-outline" style="flex:1; justify-content:center;">Éditer</button>
                    <button wire:click="toggleActif({{ $plan->id }})"
                            class="btn-sm-outline"
                            style="flex:1; justify-content:center; {{ $plan->actif ? 'color:var(--c-warning); border-color:rgba(255,171,0,0.3)' : 'color:var(--c-green); border-color:rgba(0,230,118,0.3)' }}">
                        {{ $plan->actif ? 'Désactiver' : 'Activer' }}
                    </button>
                    <button wire:click="supprimer({{ $plan->id }})"
                            wire:confirm="Supprimer ce plan ?"
                            class="btn-sm-red" style="padding:5px 12px;">✕</button>
                </div>

            </div>
        @empty
            <div style="text-align:center; padding:3rem; color:var(--c-muted); font-family:var(--font-display); letter-spacing:0.06em; text-transform:uppercase; font-size:0.85rem;">
                Aucun plan configuré
            </div>
        @endforelse
    </div>

    {{-- ── MODAL ───────────────────────────────────────────── --}}
    @if($showModal)
    <div style="position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:200; display:flex; align-items:center; justify-content:center; padding:1rem;">
        <div style="background:var(--c-bg2); border:1px solid var(--c-border-g); width:100%; max-width:420px;">

            <div style="display:flex; align-items:center; justify-content:space-between; padding:1.25rem 1.5rem; border-bottom:1px solid var(--c-border);">
                <span style="font-family:var(--font-display); font-weight:700; font-size:1rem; letter-spacing:0.06em; text-transform:uppercase;">
                    {{ $modeEdition ? 'Modifier le plan' : 'Nouveau plan' }}
                </span>
                <button wire:click="$set('showModal', false)"
                        style="background:none; border:none; color:var(--c-muted); font-size:1.2rem; cursor:pointer; padding:4px 8px;">✕</button>
            </div>

            <div style="padding:1.5rem;">

                @if($errors->any())
                    <div class="flash-error" style="margin-bottom:1rem;">
                        @foreach($errors->all() as $error)<div>✕ {{ $error }}</div>@endforeach
                    </div>
                @endif

                <div style="margin-bottom:1rem;">
                    <label style="display:block; font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.4rem;">Nom *</label>
                    <input wire:model="nom" type="text" placeholder="Ex: Hebdomadaire"
                           style="width:100%; background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:9px 12px; font-size:0.9rem; outline:none;">
                </div>

                <div style="margin-bottom:1rem;">
                    <label style="display:block; font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.4rem;">
                        Slug * <span style="font-weight:400;">(auto-généré)</span>
                    </label>
                    <input wire:model="slug" type="text" placeholder="hebdomadaire"
                           style="width:100%; background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-muted); padding:9px 12px; font-family:monospace; font-size:0.9rem; outline:none;">
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:1rem;">
                    <div>
                        <label style="display:block; font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.4rem;">Prix (XOF) *</label>
                        <input wire:model="prix" type="number" min="0" placeholder="1000"
                               style="width:100%; background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:9px 12px; font-size:0.9rem; outline:none;">
                    </div>
                    <div>
                        <label style="display:block; font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.4rem;">Durée (jours) *</label>
                        <input wire:model="duree_jours" type="number" min="1" placeholder="30"
                               style="width:100%; background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:9px 12px; font-size:0.9rem; outline:none;">
                    </div>
                </div>

                <div style="margin-bottom:1.5rem; display:flex; align-items:center; gap:0.75rem;">
                    <input wire:model="actif" type="checkbox" id="actif" style="width:16px; height:16px; cursor:pointer; flex-shrink:0;">
                    <label for="actif" style="font-family:var(--font-display); font-size:0.82rem; font-weight:600; letter-spacing:0.06em; text-transform:uppercase; color:var(--c-text); cursor:pointer;">
                        Plan actif (visible aux utilisateurs)
                    </label>
                </div>

                {{-- Séparateur investissement --}}
                <div style="border-top:1px solid var(--c-border); padding-top:1rem; margin-bottom:1rem;">
                    <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1rem;">
                        <input wire:model.live="est_investissement" type="checkbox" id="est_investissement"
                            style="width:16px; height:16px; cursor:pointer; flex-shrink:0;">
                        <label for="est_investissement" style="font-family:var(--font-display); font-size:0.82rem; font-weight:600; letter-spacing:0.06em; text-transform:uppercase; color:var(--c-green); cursor:pointer;">
                            Plan investissement
                        </label>
                    </div>

                    @if($est_investissement)
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">
                        <div>
                            <label style="display:block; font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.4rem;">
                                Taux journalier (%) *
                            </label>
                            <input wire:model="taux_journalier" type="number" min="0.01" max="100" step="0.01" placeholder="0.10"
                                style="width:100%; background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:9px 12px; font-size:0.9rem; outline:none;">
                            <div style="font-size:0.72rem; color:var(--c-muted); margin-top:4px;">
                                @if($taux_journalier && $prix)
                                    = {{ number_format($prix * $taux_journalier / 100, 0, ',', ' ') }} XOF/jour
                                @endif
                            </div>
                        </div>
                        <div>
                            <label style="display:block; font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.4rem;">
                                Seuil de retrait (XOF) *
                            </label>
                            <input wire:model="seuil_retrait" type="number" min="1" placeholder="5000"
                                style="width:100%; background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:9px 12px; font-size:0.9rem; outline:none;">
                        </div>
                    </div>
                    @endif
                </div>

                <div style="display:flex; justify-content:flex-end; gap:0.75rem; padding-top:1rem; border-top:1px solid var(--c-border);">
                    <button wire:click="$set('showModal', false)" class="btn-sm-outline">Annuler</button>
                    <button wire:click="sauvegarder" class="btn-sm-green">
                        <span wire:loading.remove wire:target="sauvegarder">{{ $modeEdition ? 'Enregistrer' : 'Créer' }}</span>
                        <span wire:loading wire:target="sauvegarder">…</span>
                    </button>
                </div>

            </div>
        </div>
    </div>
    @endif

</div>

@push('scripts')
<style>
    .plans-cards-wrap { display: none; }

    @media (max-width: 768px) {
        .plans-table-wrap { display: none; }
        .plans-cards-wrap { display: block; }
    }
</style>
@endpush