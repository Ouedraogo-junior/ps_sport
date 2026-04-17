<div>

    {{-- ── FLASH ─────────────────────────────────────────── --}}
    @if(session()->has('success'))
        <div class="flash-success">✓ &nbsp;{{ session('success') }}</div>
    @endif
    @if(session()->has('error'))
        <div class="flash-error">✕ &nbsp;{{ session('error') }}</div>
    @endif

    {{-- ── BARRE D'OUTILS ─────────────────────────────────── --}}
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; gap:1rem; flex-wrap:wrap;">
        <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
            @foreach(['en_attente' => 'En attente', 'valide' => 'Validés', 'rejete' => 'Rejetés', 'tous' => 'Tous'] as $valeur => $label)
                <button wire:click="$set('filtre', '{{ $valeur }}')"
                        style="font-family:var(--font-display); font-weight:700; font-size:0.75rem; letter-spacing:0.07em; text-transform:uppercase; padding:5px 14px; cursor:pointer; border:1px solid {{ $filtre === $valeur ? 'var(--c-green)' : 'var(--c-border)' }}; background:{{ $filtre === $valeur ? 'var(--c-green-bg)' : 'transparent' }}; color:{{ $filtre === $valeur ? 'var(--c-green)' : 'var(--c-muted)' }}; transition:all 0.2s;">
                    {{ $label }}
                </button>
            @endforeach
        </div>
        <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
            <span class="pill pill-yellow">En attente : {{ $stats['en_attente'] }}</span>
            <span class="pill pill-green">Validés : {{ $stats['valide'] }}</span>
            <span class="pill pill-red">Rejetés : {{ $stats['rejete'] }}</span>
        </div>
    </div>

    {{-- ── TABLEAU desktop ─────────────────────────────────── --}}
    <div class="retraits-table-wrap" style="background:var(--c-bg2); border:1px solid var(--c-border); overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Montant</th>
                    <th>Opérateur</th>
                    <th>Numéro</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Traité par</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($demandes as $demande)
                    <tr>
                        <td>
                            <div style="font-family:var(--font-display); font-weight:700;">
                                {{ $demande->user->nom ?? $demande->user->telephone ?? '—' }}
                            </div>
                            <div style="font-size:0.78rem; color:var(--c-muted);">{{ $demande->user->telephone ?? '' }}</div>
                        </td>
                        <td style="font-family:var(--font-display); font-weight:700; color:var(--c-green);">
                            {{ $demande->montantFormate() }}
                        </td>
                        <td>
                            <span class="pill pill-gray">{{ $demande->operateurLabel() }}</span>
                        </td>
                        <td style="font-family:monospace; color:var(--c-muted); font-size:0.85rem;">
                            {{ $demande->numero_telephone }}
                        </td>
                        <td style="color:var(--c-muted); font-size:0.82rem;">
                            {{ $demande->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            @php $badge = $demande->statutBadge(); @endphp
                            <span class="pill {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                        </td>
                        <td>
                            @if($demande->statut === 'en_attente')
                                <div style="display:flex; gap:6px;">
                                    <button wire:click="ouvrirModal({{ $demande->id }}, 'valider')" class="btn-sm-green">Valider</button>
                                    <button wire:click="ouvrirModal({{ $demande->id }}, 'rejeter')" class="btn-sm-red">Rejeter</button>
                                </div>
                            @elseif($demande->note_admin)
                                <span style="font-size:0.78rem; color:var(--c-muted); font-style:italic;">{{ $demande->note_admin }}</span>
                            @else
                                <span style="color:var(--c-muted);">—</span>
                            @endif
                        </td>

                        <td style="font-size:0.82rem; color:var(--c-muted);">
                            @if($demande->traitePar)
                                <div style="font-family:var(--font-display); font-weight:700; color:var(--c-text);">
                                    {{ $demande->traitePar->nom ?? $demande->traitePar->telephone }}
                                </div>
                                <div style="font-size:0.75rem;">{{ $demande->updated_at->format('d/m/Y H:i') }}</div>
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:3rem; color:var(--c-muted); font-family:var(--font-display); letter-spacing:0.06em; text-transform:uppercase; font-size:0.85rem;">
                            Aucune demande de retrait
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── CARTES MOBILES ───────────────────────────────────── --}}
    <div class="retraits-cards-wrap">
        @forelse($demandes as $demande)
            @php $badge = $demande->statutBadge(); @endphp
            <div style="background:var(--c-bg2); border:1px solid var(--c-border); padding:1rem; margin-bottom:0.75rem;">

                {{-- Ligne 1 : nom + statut --}}
                <div style="display:flex; align-items:center; justify-content:space-between; gap:0.5rem; margin-bottom:0.6rem;">
                    <div>
                        <div style="font-family:var(--font-display); font-weight:700; font-size:1rem;">
                            {{ $demande->user->nom ?? $demande->user->telephone ?? '—' }}
                        </div>
                        <div style="font-size:0.78rem; color:var(--c-muted);">{{ $demande->user->telephone ?? '' }}</div>
                    </div>
                    <span class="pill {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                </div>

                {{-- Ligne 2 : montant + opérateur + numéro --}}
                <div style="display:flex; gap:1rem; flex-wrap:wrap; margin-bottom:0.75rem;">
                    <div>
                        <div style="font-family:var(--font-display); font-size:0.65rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:2px;">Montant</div>
                        <span style="font-family:var(--font-display); font-weight:700; color:var(--c-green);">{{ $demande->montantFormate() }}</span>
                    </div>
                    <div>
                        <div style="font-family:var(--font-display); font-size:0.65rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:2px;">Opérateur</div>
                        <span class="pill pill-gray">{{ $demande->operateurLabel() }}</span>
                    </div>
                    <div>
                        <div style="font-family:var(--font-display); font-size:0.65rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:2px;">Numéro</div>
                        <span style="font-family:monospace; color:var(--c-muted); font-size:0.85rem;">{{ $demande->numero_telephone }}</span>
                    </div>
                    <div>
                        <div style="font-family:var(--font-display); font-size:0.65rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:2px;">Date</div>
                        <span style="color:var(--c-muted); font-size:0.82rem;">{{ $demande->created_at->format('d/m/Y H:i') }}</span>
                    </div>

                    @if($demande->traitePar)
                        <div>
                            <div style="font-family:var(--font-display); font-size:0.65rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:2px;">Traité par</div>
                            <span style="font-family:var(--font-display); font-weight:700; font-size:0.85rem;">
                                {{ $demande->traitePar->nom ?? $demande->traitePar->telephone }}
                            </span>
                        </div>
                    @endif
                </div>

                {{-- Note admin si rejeté --}}
                @if($demande->note_admin)
                    <div style="font-size:0.78rem; color:var(--c-muted); font-style:italic; margin-bottom:0.75rem;">
                        Note : {{ $demande->note_admin }}
                    </div>
                @endif

                {{-- Actions --}}
                @if($demande->statut === 'en_attente')
                    <div style="display:flex; gap:0.5rem;">
                        <button wire:click="ouvrirModal({{ $demande->id }}, 'valider')" class="btn-sm-green" style="flex:1; justify-content:center;">Valider</button>
                        <button wire:click="ouvrirModal({{ $demande->id }}, 'rejeter')" class="btn-sm-red" style="flex:1; justify-content:center;">Rejeter</button>
                    </div>
                @endif

            </div>
        @empty
            <div style="text-align:center; padding:3rem; color:var(--c-muted); font-family:var(--font-display); letter-spacing:0.06em; text-transform:uppercase; font-size:0.85rem;">
                Aucune demande de retrait
            </div>
        @endforelse
    </div>

    {{-- ── PAGINATION ───────────────────────────────────────── --}}
    <div style="margin-top:1rem;">
        {{ $demandes->links() }}
    </div>

    {{-- ── MODAL ───────────────────────────────────────────── --}}
    @if($modalOuvert && $demandeSelectionnee)
    <div style="position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:200; display:flex; align-items:center; justify-content:center; padding:1rem;">
        <div style="background:var(--c-bg2); border:1px solid var(--c-border-g); width:100%; max-width:420px;">

            {{-- Header modal --}}
            <div style="display:flex; align-items:center; justify-content:space-between; padding:1.25rem 1.5rem; border-bottom:1px solid var(--c-border);">
                <span style="font-family:var(--font-display); font-weight:700; font-size:1rem; letter-spacing:0.06em; text-transform:uppercase; color:{{ $actionModal === 'valider' ? 'var(--c-green)' : 'var(--c-danger)' }};">
                    {{ $actionModal === 'valider' ? '✓ Valider le retrait' : '✕ Rejeter la demande' }}
                </span>
                <button wire:click="fermerModal"
                        style="background:none; border:none; color:var(--c-muted); font-size:1.2rem; cursor:pointer; padding:4px 8px;">✕</button>
            </div>

            <div style="padding:1.5rem;">

                {{-- Infos demande --}}
                <div style="background:var(--c-bg3); border:1px solid var(--c-border); padding:1rem; margin-bottom:1.25rem;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.5rem;">
                        <span style="font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted);">Utilisateur</span>
                        <span style="font-family:var(--font-display); font-weight:700;">{{ $demandeSelectionnee->user->nom ?? $demandeSelectionnee->user->telephone }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.5rem;">
                        <span style="font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted);">Montant</span>
                        <span style="font-family:var(--font-display); font-weight:700; color:var(--c-green); font-size:1.1rem;">{{ $demandeSelectionnee->montantFormate() }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.5rem;">
                        <span style="font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted);">Opérateur</span>
                        <span class="pill pill-gray">{{ $demandeSelectionnee->operateurLabel() }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted);">Numéro</span>
                        <span style="font-family:monospace; font-weight:700; font-size:1rem;">{{ $demandeSelectionnee->numero_telephone }}</span>
                    </div>
                </div>

                @if($actionModal === 'valider')
                    <div style="background:rgba(0,230,118,0.06); border-left:3px solid var(--c-green); padding:10px 14px; margin-bottom:1.25rem; font-size:0.82rem; color:var(--c-muted);">
                        ⚠ Assurez-vous d'avoir effectué le virement Mobile Money avant de valider.
                    </div>
                    <div style="display:flex; gap:0.75rem;">
                        <button wire:click="valider" class="btn-sm-green" style="flex:1; justify-content:center; padding:10px;">
                            <span wire:loading.remove wire:target="valider">✓ Confirmer</span>
                            <span wire:loading wire:target="valider">…</span>
                        </button>
                        <button wire:click="fermerModal" class="btn-sm-outline" style="flex:1; justify-content:center; padding:10px;">Annuler</button>
                    </div>
                @elseif($actionModal === 'rejeter')
                    <div style="margin-bottom:1.25rem;">
                        <label style="display:block; font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.4rem;">
                            Motif du rejet <span style="font-weight:400;">(optionnel)</span>
                        </label>
                        <input wire:model.defer="noteAdmin" type="text"
                               placeholder="Ex: Numéro incorrect, solde insuffisant..."
                               style="width:100%; background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:9px 12px; font-size:0.9rem; outline:none;">
                    </div>
                    <div style="display:flex; gap:0.75rem;">
                        <button wire:click="rejeter" class="btn-sm-red" style="flex:1; justify-content:center; padding:10px;">
                            <span wire:loading.remove wire:target="rejeter">✕ Confirmer le rejet</span>
                            <span wire:loading wire:target="rejeter">…</span>
                        </button>
                        <button wire:click="fermerModal" class="btn-sm-outline" style="flex:1; justify-content:center; padding:10px;">Annuler</button>
                    </div>
                @endif

            </div>
        </div>
    </div>
    @endif

</div>

@push('scripts')
<style>
    .retraits-cards-wrap { display: none; }

    @media (max-width: 768px) {
        .retraits-table-wrap { display: none; }
        .retraits-cards-wrap { display: block; }
    }
</style>
@endpush