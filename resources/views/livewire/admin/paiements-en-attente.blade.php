<div>

    {{-- ── BARRE D'OUTILS ─────────────────────────────────── --}}
    <div style="display:flex; align-items:center; justify-content:space-between; gap:0.75rem; margin-bottom:1.5rem; flex-wrap:wrap;">

        <div style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:center; flex:1; min-width:0;">

            <input wire:model.live="recherche"
                   type="text"
                   placeholder="Téléphone ou nom…"
                   style="background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:7px 12px; font-family:var(--font-body); font-size:0.85rem; flex:1; min-width:0; max-width:220px; outline:none;">

            <select wire:model.live="filtreStatut"
                    style="background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:7px 12px; font-family:var(--font-display); font-size:0.8rem; letter-spacing:0.05em; text-transform:uppercase; outline:none; cursor:pointer; flex:1; min-width:120px;">
                <option value="en_attente">En attente</option>
                <option value="valide">Validés</option>
                <option value="rejete">Rejetés</option>
                <option value="">Tous</option>
            </select>

            <select wire:model.live="filtreOperateur"
                    style="background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:7px 12px; font-family:var(--font-display); font-size:0.8rem; letter-spacing:0.05em; text-transform:uppercase; outline:none; cursor:pointer; flex:1; min-width:130px;">
                <option value="">Tous opérateurs</option>
                <option value="orange">Orange Money</option>
                <option value="moov">Moov Money</option>
            </select>

        </div>

        <div style="font-family:var(--font-display); font-size:0.8rem; letter-spacing:0.06em; text-transform:uppercase; color:var(--c-muted); white-space:nowrap;">
            {{ $paiements->count() }} résultat(s)
        </div>
    </div>

    {{-- ── TABLEAU desktop ─────────────────────────────────── --}}
    <div class="paiements-table-wrap" style="background:var(--c-bg2); border:1px solid var(--c-border); overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Plan</th>
                    <th>Montant</th>
                    <th>Opérateur</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paiements as $paiement)
                    <tr>
                        <td>
                            <div style="font-family:var(--font-display); font-weight:600; font-size:0.9rem;">{{ $paiement->user->nom ?? '—' }}</div>
                            <div style="font-size:0.78rem; color:var(--c-muted);">{{ $paiement->user->telephone }}</div>
                        </td>
                        <td>
                            <span class="pill pill-gold" style="text-transform:capitalize;">{{ $paiement->plan }}</span>
                        </td>
                        <td style="font-family:var(--font-display); font-weight:700; color:var(--c-green);">
                            {{ $paiement->montantFormate() }}
                        </td>
                        <td style="color:var(--c-muted); font-size:0.85rem; text-transform:capitalize;">
                            {{ $paiement->operateur === 'orange' ? '🟠 Orange' : '🔵 Moov' }}
                        </td>
                        <td>
                            <span class="pill {{ match($paiement->statut) {
                                'en_attente' => 'pill-yellow',
                                'valide'     => 'pill-green',
                                'rejete'     => 'pill-red',
                                default      => 'pill-gray',
                            } }}">
                                {{ match($paiement->statut) {
                                    'en_attente' => 'En attente',
                                    'valide'     => 'Validé',
                                    'rejete'     => 'Rejeté',
                                    default      => $paiement->statut,
                                } }}
                            </span>
                        </td>
                        <td style="color:var(--c-muted); font-size:0.82rem; white-space:nowrap;">
                            {{ $paiement->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            <div style="display:flex; gap:6px; flex-wrap:wrap;">
                                <button wire:click="voirDetail({{ $paiement->id }})" class="btn-sm-outline">Détail</button>
                                @if($paiement->isEnAttente())
                                    <button wire:click="valider({{ $paiement->id }})"
                                            wire:confirm="Valider ce paiement et générer un code d'accès ?"
                                            class="btn-sm-green">✓ Valider</button>
                                    <button wire:click="ouvrirRejet({{ $paiement->id }})" class="btn-sm-red">✕ Rejeter</button>
                                @endif
                                @if($paiement->statut === 'valide')
                                    <span style="font-family:var(--font-display); font-size:0.72rem; color:var(--c-green); letter-spacing:0.06em; text-transform:uppercase;">✓ Traité</span>
                                @endif
                                @if($paiement->statut === 'rejete')
                                    <span style="font-family:var(--font-display); font-size:0.72rem; color:var(--c-danger); letter-spacing:0.06em; text-transform:uppercase;">✕ Rejeté</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:3rem; color:var(--c-muted); font-family:var(--font-display); letter-spacing:0.06em; text-transform:uppercase; font-size:0.85rem;">
                            Aucun paiement trouvé
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── CARTES MOBILES ───────────────────────────────────── --}}
    <div class="paiements-cards-wrap">
        @forelse($paiements as $paiement)
            <div style="background:var(--c-bg2); border:1px solid var(--c-border); padding:1rem; margin-bottom:0.75rem;">

                {{-- Ligne 1 : utilisateur + statut --}}
                <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:0.5rem; margin-bottom:0.75rem;">
                    <div>
                        <div style="font-family:var(--font-display); font-weight:700; font-size:0.95rem;">
                            {{ $paiement->user->nom ?? '—' }}
                        </div>
                        <div style="font-size:0.78rem; color:var(--c-muted);">{{ $paiement->user->telephone }}</div>
                    </div>
                    <span class="pill {{ match($paiement->statut) {
                        'en_attente' => 'pill-yellow',
                        'valide'     => 'pill-green',
                        'rejete'     => 'pill-red',
                        default      => 'pill-gray',
                    } }}">
                        {{ match($paiement->statut) {
                            'en_attente' => 'En attente',
                            'valide'     => 'Validé',
                            'rejete'     => 'Rejeté',
                            default      => $paiement->statut,
                        } }}
                    </span>
                </div>

                {{-- Ligne 2 : plan + montant + opérateur --}}
                <div style="display:flex; gap:1rem; flex-wrap:wrap; margin-bottom:0.75rem;">
                    <div>
                        <div style="font-family:var(--font-display); font-size:0.65rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:2px;">Plan</div>
                        <span class="pill pill-gold" style="text-transform:capitalize;">{{ $paiement->plan }}</span>
                    </div>
                    <div>
                        <div style="font-family:var(--font-display); font-size:0.65rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:2px;">Montant</div>
                        <span style="font-family:var(--font-display); font-weight:700; color:var(--c-green);">{{ $paiement->montantFormate() }}</span>
                    </div>
                    <div>
                        <div style="font-family:var(--font-display); font-size:0.65rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:2px;">Opérateur</div>
                        <span style="font-size:0.85rem;">{{ $paiement->operateur === 'orange' ? '🟠 Orange' : '🔵 Moov' }}</span>
                    </div>
                    <div>
                        <div style="font-family:var(--font-display); font-size:0.65rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:2px;">Date</div>
                        <span style="font-size:0.8rem; color:var(--c-muted);">{{ $paiement->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>

                {{-- Actions --}}
                <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                    <button wire:click="voirDetail({{ $paiement->id }})" class="btn-sm-outline" style="flex:1; justify-content:center;">
                        Détail
                    </button>
                    @if($paiement->isEnAttente())
                        <button wire:click="valider({{ $paiement->id }})"
                                wire:confirm="Valider ce paiement et générer un code d'accès ?"
                                class="btn-sm-green" style="flex:1; justify-content:center;">
                            ✓ Valider
                        </button>
                        <button wire:click="ouvrirRejet({{ $paiement->id }})"
                                class="btn-sm-red" style="flex:1; justify-content:center;">
                            ✕ Rejeter
                        </button>
                    @endif
                </div>

            </div>
        @empty
            <div style="text-align:center; padding:3rem; color:var(--c-muted); font-family:var(--font-display); letter-spacing:0.06em; text-transform:uppercase; font-size:0.85rem;">
                Aucun paiement trouvé
            </div>
        @endforelse
    </div>

    {{-- ── MODAL DÉTAIL ────────────────────────────────────── --}}
    @if($showDetailModal && $detailPaiement)
    <div style="position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:200; display:flex; align-items:flex-start; justify-content:center; padding:1.5rem 1rem; overflow-y:auto;">
        <div style="background:var(--c-bg2); border:1px solid var(--c-border-g); width:100%; max-width:460px; margin:auto;">

            <div style="display:flex; align-items:center; justify-content:space-between; padding:1.25rem 1.5rem; border-bottom:1px solid var(--c-border);">
                <span style="font-family:var(--font-display); font-weight:700; font-size:1rem; letter-spacing:0.06em; text-transform:uppercase;">
                    Détail paiement
                </span>
                <button wire:click="$set('showDetailModal', false)"
                        style="background:none; border:none; color:var(--c-muted); font-size:1.2rem; cursor:pointer; padding:4px 8px;">✕</button>
            </div>

            <div style="padding:1.5rem;">

                <div style="background:var(--c-bg3); padding:1rem; margin-bottom:1rem;">
                    <div style="font-family:var(--font-display); font-size:0.7rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.5rem;">Utilisateur</div>
                    <div style="font-family:var(--font-display); font-weight:700; font-size:1rem;">{{ $detailPaiement->user->nom ?? '—' }}</div>
                    <div style="color:var(--c-muted); font-size:0.85rem; margin-top:2px;">{{ $detailPaiement->user->telephone }}</div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:1rem;">
                    <div style="background:var(--c-bg3); padding:0.75rem;">
                        <div style="font-family:var(--font-display); font-size:0.68rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.3rem;">Plan</div>
                        <div style="font-family:var(--font-display); font-weight:700; text-transform:capitalize;">{{ $detailPaiement->plan }}</div>
                    </div>
                    <div style="background:var(--c-bg3); padding:0.75rem;">
                        <div style="font-family:var(--font-display); font-size:0.68rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.3rem;">Montant</div>
                        <div style="font-family:var(--font-display); font-weight:700; color:var(--c-green);">{{ $detailPaiement->montantFormate() }}</div>
                    </div>
                    <div style="background:var(--c-bg3); padding:0.75rem;">
                        <div style="font-family:var(--font-display); font-size:0.68rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.3rem;">Opérateur</div>
                        <div style="text-transform:capitalize;">{{ $detailPaiement->operateur === 'orange' ? '🟠 Orange Money' : '🔵 Moov Money' }}</div>
                    </div>
                    <div style="background:var(--c-bg3); padding:0.75rem;">
                        <div style="font-family:var(--font-display); font-size:0.68rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.3rem;">Statut</div>
                        <span class="pill {{ match($detailPaiement->statut) {
                            'en_attente' => 'pill-yellow',
                            'valide'     => 'pill-green',
                            'rejete'     => 'pill-red',
                            default      => 'pill-gray',
                        } }}">
                            {{ match($detailPaiement->statut) {
                                'en_attente' => 'En attente',
                                'valide'     => 'Validé',
                                'rejete'     => 'Rejeté',
                                default      => $detailPaiement->statut,
                            } }}
                        </span>
                    </div>
                </div>

                @if($detailPaiement->capture_path)
                    <div style="margin-bottom:1rem;">
                        <div style="font-family:var(--font-display); font-size:0.68rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.5rem;">Capture d'écran</div>
                        <a href="{{ Storage::url($detailPaiement->capture_path) }}" target="_blank">
                            <img src="{{ Storage::url($detailPaiement->capture_path) }}"
                                alt="Capture paiement"
                                style="width:100%; border:1px solid var(--c-border); max-height:300px; object-fit:contain; background:var(--c-bg3);">
                        </a>
                    </div>
                @endif

                @if($detailPaiement->note_admin)
                    <div style="background:var(--c-bg3); padding:0.75rem; margin-bottom:0.75rem;">
                        <div style="font-family:var(--font-display); font-size:0.68rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.3rem;">Note admin</div>
                        <div style="font-size:0.85rem; color:var(--c-text);">{{ $detailPaiement->note_admin }}</div>
                    </div>
                @endif

                @if($detailPaiement->motif_rejet)
                    <div style="background:rgba(255,61,61,0.06); border:1px solid rgba(255,61,61,0.2); padding:0.75rem; margin-bottom:0.75rem;">
                        <div style="font-family:var(--font-display); font-size:0.68rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-danger); margin-bottom:0.3rem;">Motif de rejet</div>
                        <div style="font-size:0.85rem; color:var(--c-text);">{{ $detailPaiement->motif_rejet }}</div>
                    </div>
                @endif

                <div style="font-size:0.78rem; color:var(--c-muted); text-align:right;">
                    Soumis le {{ $detailPaiement->created_at->format('d/m/Y à H:i') }}
                    @if($detailPaiement->traite_le)
                        — Traité le {{ $detailPaiement->traite_le->format('d/m/Y à H:i') }}
                    @endif
                </div>

                @if($detailPaiement->isEnAttente())
                    <div style="display:flex; gap:0.75rem; margin-top:1.25rem; padding-top:1rem; border-top:1px solid var(--c-border);">
                        <button wire:click="valider({{ $detailPaiement->id }})"
                                wire:confirm="Valider ce paiement ?"
                                class="btn-sm-green" style="flex:1; justify-content:center;">
                            ✓ Valider
                        </button>
                        <button wire:click="ouvrirRejet({{ $detailPaiement->id }})"
                                class="btn-sm-red" style="flex:1; justify-content:center;">
                            ✕ Rejeter
                        </button>
                    </div>
                @endif

            </div>
        </div>
    </div>
    @endif

    {{-- ── MODAL REJET ─────────────────────────────────────── --}}
    @if($showRejetModal)
    <div style="position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:300; display:flex; align-items:center; justify-content:center; padding:1rem;">
        <div style="background:var(--c-bg2); border:1px solid rgba(255,61,61,0.3); width:100%; max-width:400px;">

            <div style="display:flex; align-items:center; justify-content:space-between; padding:1.25rem 1.5rem; border-bottom:1px solid var(--c-border);">
                <span style="font-family:var(--font-display); font-weight:700; font-size:1rem; letter-spacing:0.06em; text-transform:uppercase; color:var(--c-danger);">
                    Rejeter le paiement
                </span>
                <button wire:click="$set('showRejetModal', false)"
                        style="background:none; border:none; color:var(--c-muted); font-size:1.2rem; cursor:pointer; padding:4px 8px;">✕</button>
            </div>

            <div style="padding:1.5rem;">
                <label style="display:block; font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.5rem;">
                    Motif de rejet <span style="font-weight:400;">(optionnel)</span>
                </label>
                <textarea wire:model="motifRejet"
                          rows="3"
                          placeholder="Ex: Capture d'écran illisible, montant incorrect…"
                          style="width:100%; background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:9px 12px; font-family:var(--font-body); font-size:0.9rem; outline:none; resize:vertical; margin-bottom:1.25rem;"></textarea>

                <div style="display:flex; justify-content:flex-end; gap:0.75rem;">
                    <button wire:click="$set('showRejetModal', false)" class="btn-sm-outline">Annuler</button>
                    <button wire:click="confirmerRejet" class="btn-sm-red">
                        <span wire:loading.remove wire:target="confirmerRejet">Confirmer le rejet</span>
                        <span wire:loading wire:target="confirmerRejet">…</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

@push('scripts')
<style>
    .paiements-cards-wrap { display: none; }

    @media (max-width: 768px) {
        .paiements-table-wrap { display: none; }
        .paiements-cards-wrap { display: block; }
    }
</style>
@endpush