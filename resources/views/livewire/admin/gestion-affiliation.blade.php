<div>

    {{-- Flash --}}
    @if (session('success'))
        <div class="flash-success">✓ &nbsp;{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="flash-error">✕ &nbsp;{{ session('error') }}</div>
    @endif

    {{-- Stats --}}
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:1.75rem;">
        <div class="stat-card">
            <div class="stat-label">Parrains actifs</div>
            <div class="stat-value">{{ $stats['total_parrains'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total filleuls</div>
            <div class="stat-value green">{{ $stats['total_filleuls'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Soldes affiliation cumulés</div>
            <div class="stat-value gold">{{ number_format($stats['total_credite'], 0, ',', ' ') }} <span style="font-size:1rem;">FCFA</span></div>
        </div>
    </div>

    {{-- Onglets --}}
    <div style="display:flex; gap:0; border-bottom:1px solid var(--c-border); margin-bottom:1.5rem;">
        @foreach(['parrains' => 'Parrains', 'paliers' => 'Paliers', 'historique' => 'Historique'] as $key => $label)
            <button wire:click="$set('onglet', '{{ $key }}')"
                style="font-family:var(--font-display); font-weight:700; font-size:0.8rem; letter-spacing:0.08em; text-transform:uppercase; padding:10px 20px; background:none; border:none; border-bottom:2px solid {{ $onglet === $key ? 'var(--c-green)' : 'transparent' }}; color:{{ $onglet === $key ? 'var(--c-green)' : 'var(--c-muted)' }}; cursor:pointer; transition:all 0.2s; margin-bottom:-1px;">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Recherche --}}
    @if(in_array($onglet, ['parrains', 'historique']))
        <div style="margin-bottom:1rem;">
            <input wire:model.live.debounce.300ms="recherche" type="text"
                placeholder="Rechercher..."
                style="background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:8px 14px; font-family:var(--font-body); font-size:0.875rem; width:300px; outline:none; transition:border-color 0.2s;"
                onfocus="this.style.borderColor='var(--c-green)'"
                onblur="this.style.borderColor='var(--c-border)'">
        </div>
    @endif

    {{-- ── ONGLET PARRAINS ── --}}
    @if($onglet === 'parrains')
        <div style="background:var(--c-bg2); border:1px solid var(--c-border);">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Parrain</th>
                        <th>Téléphone</th>
                        <th>Code affiliation</th>
                        <th style="text-align:center;">Filleuls (total)</th>
                        <th style="text-align:center;">Depuis dernier retrait</th>
                        <th style="text-align:right;">Solde affiliation</th>
                        <th style="text-align:right;">Total cumulé</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($parrains as $parrain)
                        <tr>
                            <td style="font-weight:600; color:var(--c-text);">{{ $parrain->nom }}</td>
                            <td style="color:var(--c-muted);">{{ $parrain->telephone }}</td>
                            <td>
                                <span style="font-family:monospace; font-size:0.8rem; background:var(--c-bg3); border:1px solid var(--c-border-g); color:var(--c-green); padding:2px 8px;">
                                    {{ $parrain->referralCode?->code ?? '—' }}
                                </span>
                            </td>
                            <td style="text-align:center;">
                                <span class="pill pill-green">{{ $parrain->filleuls_count }}</span>
                            </td>
                            <td style="text-align:center;">
                                <span class="pill pill-gray">{{ $parrain->soldeAffiliation->filleuls_depuis_retrait ?? 0 }}</span>
                            </td>
                            <td style="text-align:right; font-weight:700; color:var(--c-green);">
                                {{ number_format($parrain->soldeAffiliation->solde ?? 0, 0, ',', ' ') }} FCFA
                            </td>
                            <td style="text-align:right; font-weight:700; color:var(--c-gold);">
                                {{ number_format($parrain->soldeAffiliation->total_cumule ?? 0, 0, ',', ' ') }} FCFA
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center; color:var(--c-muted); padding:2.5rem;">
                                Aucun parrain pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div style="padding:1rem; border-top:1px solid var(--c-border);">
                {{ $parrains->links() }}
            </div>
        </div>
    @endif

    {{-- ── ONGLET PALIERS ── --}}
    @if($onglet === 'paliers')
        <div style="background:var(--c-bg2); border:1px solid var(--c-border);">
            <div style="padding:1rem 1.25rem; border-bottom:1px solid var(--c-border); display:flex; align-items:center; justify-content:space-between;">
                <div style="font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted);">
                    Paliers de bonus
                </div>
                <button wire:click="ouvrirModalPalier()" class="btn-sm-green">
                    + Nouveau palier
                </button>
            </div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Seuil (nb filleuls depuis retrait)</th>
                        <th>Bonus</th>
                        <th style="text-align:center;">Statut</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paliers as $palier)
                        <tr>
                            <td style="font-weight:700; color:var(--c-text);">{{ $palier->seuil }} filleuls</td>
                            <td style="font-weight:700; color:var(--c-green);">{{ $palier->montantFormate() }}</td>
                            <td style="text-align:center;">
                                <button wire:click="toggleActifPalier({{ $palier->id }})"
                                    class="pill {{ $palier->actif ? 'pill-green' : 'pill-gray' }}"
                                    style="cursor:pointer; border:none; background:none;">
                                    {{ $palier->actif ? 'Actif' : 'Inactif' }}
                                </button>
                            </td>
                            <td style="text-align:right;">
                                <button wire:click="ouvrirModalPalier({{ $palier->id }})" class="btn-sm-outline" style="margin-right:6px;">
                                    Modifier
                                </button>
                                <button wire:click="confirmerSuppression({{ $palier->id }})" class="btn-sm-red">
                                    Supprimer
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center; color:var(--c-muted); padding:2.5rem;">
                                Aucun palier configuré.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    {{-- ── ONGLET HISTORIQUE ── --}}
    @if($onglet === 'historique')
        <div style="background:var(--c-bg2); border:1px solid var(--c-border);">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Parrain</th>
                        <th>Filleul</th>
                        <th style="text-align:center;">Statut</th>
                        <th style="text-align:right;">Bonus crédité</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($historique as $referral)
                        <tr>
                            <td style="color:var(--c-muted); white-space:nowrap;">{{ $referral->created_at->format('d/m/Y H:i') }}</td>
                            <td style="font-weight:600;">{{ $referral->parrain->nom }}</td>
                            <td style="color:var(--c-muted);">{{ $referral->filleul->nom }}</td>
                            <td style="text-align:center;">
                                <span class="pill {{ $referral->statut === 'credite' ? 'pill-green' : 'pill-gray' }}">
                                    {{ $referral->statut }}
                                </span>
                            </td>
                            <td style="text-align:right; font-weight:700; color:var(--c-green);">
                                {{ $referral->bonus_affiliation > 0 ? number_format($referral->bonus_affiliation, 0, ',', ' ') . ' FCFA' : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; color:var(--c-muted); padding:2.5rem;">
                                Aucun historique.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div style="padding:1rem; border-top:1px solid var(--c-border);">
                {{ $historique->links() }}
            </div>
        </div>
    @endif

    {{-- ── MODAL PALIER ── --}}
    @if($modalPalier)
        <div style="position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:100; display:flex; align-items:center; justify-content:center;">
            <div style="background:var(--c-bg2); border:1px solid var(--c-border-g); padding:1.75rem; width:100%; max-width:400px;">
                <div style="font-family:var(--font-display); font-weight:800; font-size:1rem; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-text); margin-bottom:1.25rem;">
                    {{ $palierEditId ? 'Modifier le palier' : 'Nouveau palier' }}
                </div>

                <div style="margin-bottom:1rem;">
                    <label style="display:block; font-family:var(--font-display); font-size:0.7rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:6px;">
                        Seuil (filleuls depuis dernier retrait)
                    </label>
                    <input wire:model="palierSeuil" type="number" min="1"
                        style="width:100%; background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:9px 12px; font-family:var(--font-body); font-size:0.875rem; outline:none;"
                        onfocus="this.style.borderColor='var(--c-green)'"
                        onblur="this.style.borderColor='var(--c-border)'">
                    @error('palierSeuil')
                        <p style="color:var(--c-danger); font-size:0.75rem; margin-top:4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom:1.5rem;">
                    <label style="display:block; font-family:var(--font-display); font-size:0.7rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:6px;">
                        Montant du bonus (FCFA)
                    </label>
                    <input wire:model="palierMontant" type="number" min="0" step="100"
                        style="width:100%; background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:9px 12px; font-family:var(--font-body); font-size:0.875rem; outline:none;"
                        onfocus="this.style.borderColor='var(--c-green)'"
                        onblur="this.style.borderColor='var(--c-border)'">
                    @error('palierMontant')
                        <p style="color:var(--c-danger); font-size:0.75rem; margin-top:4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="display:flex; justify-content:flex-end; gap:0.75rem;">
                    <button wire:click="$set('modalPalier', false)" class="btn-sm-outline">
                        Annuler
                    </button>
                    <button wire:click="sauvegarderPalier" class="btn-sm-green">
                        Enregistrer
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- ── MODAL SUPPRESSION ── --}}
    @if($modalSupprimer)
        <div style="position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:100; display:flex; align-items:center; justify-content:center;">
            <div style="background:var(--c-bg2); border:1px solid rgba(255,61,61,0.3); padding:1.75rem; width:100%; max-width:380px;">
                <div style="font-family:var(--font-display); font-weight:800; font-size:1rem; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-danger); margin-bottom:0.75rem;">
                    Confirmer la suppression
                </div>
                <p style="color:var(--c-muted); font-size:0.875rem; margin-bottom:1.5rem;">
                    Ce palier sera définitivement supprimé.
                </p>
                <div style="display:flex; justify-content:flex-end; gap:0.75rem;">
                    <button wire:click="$set('modalSupprimer', false)" class="btn-sm-outline">
                        Annuler
                    </button>
                    <button wire:click="supprimerPalier" class="btn-sm-red">
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>