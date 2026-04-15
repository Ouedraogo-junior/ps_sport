<div>

    {{-- ── BARRE D'OUTILS ─────────────────────────────────── --}}
    <div style="display:flex; align-items:center; justify-content:space-between; gap:0.75rem; margin-bottom:1.5rem; flex-wrap:wrap;">

        <div style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:center; flex:1; min-width:0;">

            <input wire:model.live="recherche" type="text" placeholder="Téléphone ou nom…"
                   style="background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:7px 12px; font-family:var(--font-body); font-size:0.85rem; flex:1; min-width:0; max-width:220px; outline:none;">

            <select wire:model.live="filtreStatut"
                    style="background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:7px 12px; font-family:var(--font-display); font-size:0.8rem; letter-spacing:0.05em; text-transform:uppercase; outline:none; cursor:pointer; flex:1; min-width:120px;">
                <option value="">Tous</option>
                <option value="actif">Actifs</option>
                <option value="bloque">Bloqués</option>
            </select>

        </div>

        <div style="font-family:var(--font-display); font-size:0.8rem; letter-spacing:0.06em; text-transform:uppercase; color:var(--c-muted); white-space:nowrap;">
            {{ $utilisateurs->count() }} utilisateur(s)
        </div>
    </div>

    {{-- ── TABLEAU desktop ─────────────────────────────────── --}}
    <div class="users-table-wrap" style="background:var(--c-bg2); border:1px solid var(--c-border); overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Statut compte</th>
                    <th>Abonnement</th>
                    <th>Paiements</th>
                    <th>Inscription</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($utilisateurs as $user)
                    <tr>
                        <td>
                            <div style="font-family:var(--font-display); font-weight:600; font-size:0.9rem;">
                                {{ $user->nom ?? '—' }}
                            </div>
                            <a href="https://wa.me/{{ $user->telephone }}" target="_blank"
                               style="font-size:0.78rem; color:var(--c-green); text-decoration:none; display:inline-flex; align-items:center; gap:4px; margin-top:2px; transition:opacity 0.2s;"
                               onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                {{ $user->telephone }}
                            </a>
                        </td>
                        <td>
                            <span class="pill {{ $user->isBloque() ? 'pill-red' : 'pill-green' }}">
                                {{ $user->isBloque() ? 'Bloqué' : 'Actif' }}
                            </span>
                        </td>
                        <td>
                            @if($user->abonnementActif)
                                <span class="pill pill-green">{{ ucfirst($user->abonnementActif->plan) }}</span>
                                <div style="font-size:0.75rem; color:var(--c-muted); margin-top:3px;">
                                    {{ $user->abonnementActif->joursRestants() }}j restants
                                </div>
                            @else
                                <span class="pill pill-gray">Inactif</span>
                            @endif
                        </td>
                        <td style="color:var(--c-muted); font-size:0.85rem; text-align:center;">
                            {{ $user->paiements_count }}
                        </td>
                        <td style="color:var(--c-muted); font-size:0.82rem; white-space:nowrap;">
                            {{ $user->created_at->format('d/m/Y') }}
                        </td>
                        <td>
                            <div style="display:flex; gap:6px; flex-wrap:wrap;">
                                <button wire:click="voirDetail({{ $user->id }})" class="btn-sm-outline">Détail</button>
                                @if($user->isBloque())
                                    <button wire:click="debloquer({{ $user->id }})" wire:confirm="Débloquer ce compte ?" class="btn-sm-green">Débloquer</button>
                                @else
                                    <button wire:click="bloquer({{ $user->id }})" wire:confirm="Bloquer ce compte ?" class="btn-sm-red">Bloquer</button>
                                @endif
                                <button wire:click="ouvrirReset({{ $user->id }})" class="btn-sm-outline">🔑 Réinitialiser </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:3rem; color:var(--c-muted); font-family:var(--font-display); letter-spacing:0.06em; text-transform:uppercase; font-size:0.85rem;">
                            Aucun utilisateur trouvé
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── CARTES MOBILES ───────────────────────────────────── --}}
    <div class="users-cards-wrap">
        @forelse($utilisateurs as $user)
            <div style="background:var(--c-bg2); border:1px solid var(--c-border); padding:1rem; margin-bottom:0.75rem;">

                {{-- Ligne 1 : nom + statut --}}
                <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:0.5rem; margin-bottom:0.6rem;">
                    <div>
                        <div style="font-family:var(--font-display); font-weight:700; font-size:0.95rem;">
                            {{ $user->nom ?? '—' }}
                        </div>
                        <a href="https://wa.me/{{ $user->telephone }}" target="_blank"
                           style="font-size:0.78rem; color:var(--c-green); text-decoration:none; display:inline-flex; align-items:center; gap:4px; margin-top:3px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            {{ $user->telephone }}
                        </a>
                    </div>
                    <span class="pill {{ $user->isBloque() ? 'pill-red' : 'pill-green' }}">
                        {{ $user->isBloque() ? 'Bloqué' : 'Actif' }}
                    </span>
                </div>

                {{-- Ligne 2 : abonnement + paiements + inscription --}}
                <div style="display:flex; gap:1rem; flex-wrap:wrap; margin-bottom:0.75rem;">
                    <div>
                        <div style="font-family:var(--font-display); font-size:0.65rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:2px;">Abonnement</div>
                        @if($user->abonnementActif)
                            <span class="pill pill-green">{{ ucfirst($user->abonnementActif->plan) }}</span>
                            <span style="font-size:0.75rem; color:var(--c-muted); margin-left:4px;">{{ $user->abonnementActif->joursRestants() }}j</span>
                        @else
                            <span class="pill pill-gray">Inactif</span>
                        @endif
                    </div>
                    <div>
                        <div style="font-family:var(--font-display); font-size:0.65rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:2px;">Paiements</div>
                        <span style="font-family:var(--font-display); font-weight:700; color:var(--c-text);">{{ $user->paiements_count }}</span>
                    </div>
                    <div>
                        <div style="font-family:var(--font-display); font-size:0.65rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:2px;">Inscrit le</div>
                        <span style="font-size:0.82rem; color:var(--c-muted);">{{ $user->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>

                {{-- Actions --}}
                <div style="display:flex; gap:0.5rem;">
                    <button wire:click="voirDetail({{ $user->id }})" class="btn-sm-outline" style="flex:1; justify-content:center;">Détail</button>
                    @if($user->isBloque())
                        <button wire:click="debloquer({{ $user->id }})" wire:confirm="Débloquer ce compte ?" class="btn-sm-green" style="flex:1; justify-content:center;">Débloquer</button>
                    @else
                        <button wire:click="bloquer({{ $user->id }})" wire:confirm="Bloquer ce compte ?" class="btn-sm-red" style="flex:1; justify-content:center;">Bloquer</button>
                    @endif
                    <button wire:click="ouvrirReset({{ $user->id }})" class="btn-sm-outline" style="flex:1; justify-content:center;">🔑 Réinitialiser</button>
                </div>

            </div>
        @empty
            <div style="text-align:center; padding:3rem; color:var(--c-muted); font-family:var(--font-display); letter-spacing:0.06em; text-transform:uppercase; font-size:0.85rem;">
                Aucun utilisateur trouvé
            </div>
        @endforelse
    </div>

    {{-- ── MODAL DÉTAIL ────────────────────────────────────── --}}
    @if($showDetailModal && $detailUser)
    <div style="position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:200; display:flex; align-items:flex-start; justify-content:center; padding:1rem; overflow-y:auto;">
        <div style="background:var(--c-bg2); border:1px solid var(--c-border-g); width:100%; max-width:600px; flex-shrink:0; margin:auto;">

            {{-- Header --}}
            <div style="display:flex; align-items:center; justify-content:space-between; padding:1.25rem 1.5rem; border-bottom:1px solid var(--c-border); gap:0.75rem; flex-wrap:wrap;">
                <div style="display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap; min-width:0;">
                    <span style="font-family:var(--font-display); font-weight:700; font-size:1rem; letter-spacing:0.06em; text-transform:uppercase;">
                        {{ $detailUser->nom ?? $detailUser->telephone }}
                    </span>
                    <span class="pill {{ $detailUser->isBloque() ? 'pill-red' : 'pill-green' }}">
                        {{ $detailUser->isBloque() ? 'Bloqué' : 'Actif' }}
                    </span>
                </div>
                <button wire:click="$set('showDetailModal', false)"
                        style="background:none; border:none; color:var(--c-muted); font-size:1.2rem; cursor:pointer; padding:4px 8px; flex-shrink:0;">✕</button>
            </div>

            <div style="padding:1.5rem;">

                {{-- Infos générales --}}
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:1.5rem;">
                    <div style="background:var(--c-bg3); padding:0.75rem;">
                        <div style="font-family:var(--font-display); font-size:0.68rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.3rem;">Téléphone</div>
                        <a href="https://wa.me/{{ $detailUser->telephone }}" target="_blank"
                           style="font-family:var(--font-display); font-weight:700; color:var(--c-green); text-decoration:none; display:inline-flex; align-items:center; gap:6px; word-break:break-all;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="currentColor" style="flex-shrink:0;"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            {{ $detailUser->telephone }}
                        </a>
                    </div>
                    <div style="background:var(--c-bg3); padding:0.75rem;">
                        <div style="font-family:var(--font-display); font-size:0.68rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.3rem;">Inscrit le</div>
                        <div>{{ $detailUser->created_at->format('d/m/Y à H:i') }}</div>
                    </div>
                </div>

                {{-- Abonnement actif --}}
                <div style="margin-bottom:1.5rem;">
                    <div style="font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.5rem;">
                        Abonnement actuel
                    </div>
                    @if($detailUser->abonnementActif)
                        <div style="background:rgba(0,230,118,0.06); border:1px solid rgba(0,230,118,0.2); padding:0.75rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.5rem;">
                            <div style="display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap;">
                                <span class="pill pill-green" style="text-transform:capitalize;">{{ $detailUser->abonnementActif->plan }}</span>
                                <span style="font-size:0.82rem; color:var(--c-muted);">
                                    jusqu'au {{ $detailUser->abonnementActif->date_fin->format('d/m/Y') }}
                                </span>
                            </div>
                            <div style="font-family:var(--font-display); font-weight:700; color:var(--c-green);">
                                {{ $detailUser->abonnementActif->joursRestants() }}j
                            </div>
                        </div>
                    @else
                        <div style="background:var(--c-bg3); padding:0.75rem; color:var(--c-muted); font-size:0.85rem; font-family:var(--font-display); letter-spacing:0.05em; text-transform:uppercase;">
                            Aucun abonnement actif
                        </div>
                    @endif
                </div>

                {{-- Historique abonnements --}}
                @if($detailUser->abonnements->count() > 0)
                    <div style="margin-bottom:1.5rem;">
                        <div style="font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.5rem;">
                            Derniers abonnements
                        </div>
                        @foreach($detailUser->abonnements as $abo)
                            <div style="padding:7px 0; border-bottom:1px solid var(--c-border); font-size:0.83rem;">
                                <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.4rem;">
                                    <span style="text-transform:capitalize; color:var(--c-text);">{{ $abo->plan }}</span>
                                    <span class="pill {{ $abo->isActif() ? 'pill-green' : 'pill-gray' }}" style="font-size:0.65rem;">
                                        {{ $abo->isActif() ? 'Actif' : 'Expiré' }}
                                    </span>
                                </div>
                                <div style="color:var(--c-muted); font-size:0.78rem; margin-top:2px;">
                                    {{ $abo->date_debut->format('d/m/Y') }} → {{ $abo->date_fin->format('d/m/Y') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Derniers paiements --}}
                @if($detailUser->paiements->count() > 0)
                    <div style="margin-bottom:1.5rem;">
                        <div style="font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.5rem;">
                            Derniers paiements
                        </div>
                        @foreach($detailUser->paiements as $paiement)
                            <div style="padding:7px 0; border-bottom:1px solid var(--c-border); font-size:0.83rem;">
                                <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.4rem;">
                                    <span style="text-transform:capitalize; color:var(--c-muted);">
                                        {{ $paiement->operateur === 'orange' ? '🟠' : '🔵' }} {{ ucfirst($paiement->plan) }}
                                    </span>
                                    <span style="font-family:var(--font-display); font-weight:700; color:var(--c-green);">
                                        {{ $paiement->montantFormate() }}
                                    </span>
                                    <span class="pill {{ match($paiement->statut) {
                                        'valide'     => 'pill-green',
                                        'en_attente' => 'pill-yellow',
                                        'rejete'     => 'pill-red',
                                        default      => 'pill-gray',
                                    } }}" style="font-size:0.65rem;">
                                        {{ match($paiement->statut) {
                                            'valide'     => 'Validé',
                                            'en_attente' => 'En attente',
                                            'rejete'     => 'Rejeté',
                                            default      => $paiement->statut,
                                        } }}
                                    </span>
                                    <span style="color:var(--c-muted); font-size:0.78rem;">
                                        {{ $paiement->created_at->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Codes d'accès --}}
                @if($detailUser->accessCodes->count() > 0)
                    <div style="margin-bottom:1.5rem;">
                        <div style="font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.5rem;">
                            Codes d'accès
                        </div>
                        @foreach($detailUser->accessCodes as $code)
                            <div style="padding:7px 0; border-bottom:1px solid var(--c-border); font-size:0.83rem;">
                                <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.4rem;">
                                    <span style="font-family:monospace; color:var(--c-gold); letter-spacing:0.06em;">{{ $code->code }}</span>
                                    <span style="text-transform:capitalize; color:var(--c-muted);">{{ $code->plan }}</span>
                                    <span class="pill {{ match($code->statut) {
                                        'actif'   => 'pill-green',
                                        'utilise' => 'pill-gray',
                                        'revoque' => 'pill-red',
                                        default   => 'pill-gray',
                                    } }}" style="font-size:0.65rem;">
                                        {{ ucfirst($code->statut) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Actions --}}
                <div style="display:flex; justify-content:flex-end; gap:0.75rem; padding-top:1rem; border-top:1px solid var(--c-border); flex-wrap:wrap;">
                    <button wire:click="$set('showDetailModal', false)" class="btn-sm-outline">Fermer</button>
                    @if($detailUser->isBloque())
                        <button wire:click="debloquer({{ $detailUser->id }})" wire:confirm="Débloquer ce compte ?" class="btn-sm-green">Débloquer</button>
                    @else
                        <button wire:click="bloquer({{ $detailUser->id }})" wire:confirm="Bloquer ce compte ?" class="btn-sm-red">Bloquer</button>
                    @endif
                </div>

            </div>
        </div>
    </div>
    @endif

    {{-- Modal de réinitialisation --}}
    @if($showResetModal)
    <div style="position:fixed; inset:0; background:rgba(0,0,0,0.75); z-index:300; display:flex; align-items:center; justify-content:center; padding:1rem;">
        <div style="background:var(--c-bg2); border:1px solid var(--c-border-g); width:100%; max-width:400px;">

            <div style="display:flex; align-items:center; justify-content:space-between; padding:1.25rem 1.5rem; border-bottom:1px solid var(--c-border);">
                <span style="font-family:var(--font-display); font-weight:700; font-size:0.95rem; letter-spacing:0.06em; text-transform:uppercase;">
                    Réinitialiser le mot de passe
                </span>
                <button wire:click="$set('showResetModal', false)"
                        style="background:none; border:none; color:var(--c-muted); font-size:1.2rem; cursor:pointer; padding:4px 8px;">✕</button>
            </div>

            <div style="padding:1.5rem; display:flex; flex-direction:column; gap:1rem;">

                @if(!$motDePasseGenere)
                    <p style="color:var(--c-muted); font-size:0.88rem; line-height:1.6; margin:0;">
                        Un mot de passe à 6 chiffres sera généré automatiquement et appliqué immédiatement au compte.
                    </p>
                    <div style="display:flex; justify-content:flex-end; gap:0.75rem;">
                        <button wire:click="$set('showResetModal', false)" class="btn-sm-outline">Annuler</button>
                        <button wire:click="resetMotDePasse" wire:confirm="Réinitialiser le mot de passe de cet utilisateur ?" class="btn-sm-green">Générer</button>
                    </div>

                @else
                    <p style="color:var(--c-muted); font-size:0.85rem; margin:0;">
                        Mot de passe appliqué. Copiez-le et transmettez-le à l'utilisateur.
                    </p>

                    <div style="display:flex; align-items:center; gap:0.75rem; background:var(--c-bg3); border:1px solid var(--c-border-g); padding:0.75rem 1rem;">
                        <span id="mdp-genere" style="font-family:monospace; font-size:1.4rem; font-weight:700; color:var(--c-green); letter-spacing:0.2em; flex:1;">
                            {{ $nouveauMotDePasse }}
                        </span>
                        <button onclick="navigator.clipboard.writeText('{{ $nouveauMotDePasse }}').then(() => { this.textContent = '✓'; setTimeout(() => this.textContent = 'Copier', 1500); })"
                                style="background:none; border:1px solid var(--c-border); color:var(--c-muted); padding:5px 10px; font-family:var(--font-display); font-size:0.75rem; cursor:pointer; white-space:nowrap; transition:color 0.2s;"
                                onmouseover="this.style.color='var(--c-green)'" onmouseout="this.style.color='var(--c-muted)'">
                            Copier
                        </button>
                    </div>

                    <div style="display:flex; justify-content:flex-end;">
                        <button wire:click="$set('showResetModal', false)" class="btn-sm-outline">Fermer</button>
                    </div>
                @endif

            </div>
        </div>
    </div>
    @endif

</div>

@push('scripts')
<style>
    .users-cards-wrap { display: none; }

    @media (max-width: 768px) {
        .users-table-wrap { display: none; }
        .users-cards-wrap { display: block; }
    }
</style>
@endpush