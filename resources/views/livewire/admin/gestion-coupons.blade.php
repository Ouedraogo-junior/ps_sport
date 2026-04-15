<div>

    {{-- ── BARRE D'OUTILS ─────────────────────────────────── --}}
    <div style="display:flex; align-items:center; justify-content:space-between; gap:0.75rem; margin-bottom:1.5rem; flex-wrap:wrap;">

        <div style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:center; flex:1; min-width:0;">

            <input wire:model.live="recherche"
                   type="text"
                   placeholder="Rechercher un coupon…"
                   style="background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:7px 12px; font-family:var(--font-body); font-size:0.85rem; flex:1; min-width:0; max-width:220px; outline:none;">

            <select wire:model.live="filtreStatut"
                    style="background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:7px 12px; font-family:var(--font-display); font-size:0.8rem; letter-spacing:0.05em; text-transform:uppercase; outline:none; cursor:pointer; flex:1; min-width:120px;">
                <option value="">Tous statuts</option>
                <option value="publie">Publié</option>
                <option value="depublie">Dépublié</option>
            </select>

            <select wire:model.live="filtreRisque"
                    style="background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:7px 12px; font-family:var(--font-display); font-size:0.8rem; letter-spacing:0.05em; text-transform:uppercase; outline:none; cursor:pointer; flex:1; min-width:120px;">
                <option value="">Tous risques</option>
                <option value="faible">Faible</option>
                <option value="modere">Modéré</option>
                <option value="risque">Risqué</option>
            </select>
        </div>

        <button wire:click="ouvrir" class="btn-sm-green" style="white-space:nowrap;">
            + Nouveau coupon
        </button>
    </div>

    {{-- ── TABLEAU desktop ─────────────────────────────────── --}}
    <div class="coupons-table-wrap" style="background:var(--c-bg2); border:1px solid var(--c-border); overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Risque</th>
                    <th>Sélections</th>
                    <th>Bookmakers</th>
                    <th>Publication</th>
                    <th>Résultat</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                    <tr>
                        <td>
                            <span style="font-family:var(--font-display); font-weight:600; font-size:0.95rem;">
                                {{ $coupon->titre }}
                            </span>
                        </td>
                        <td>
                            <span class="pill {{ match($coupon->niveau_risque) {
                                'faible' => 'pill-green',
                                'modere' => 'pill-yellow',
                                'risque' => 'pill-red',
                                default  => 'pill-gray',
                            } }}">{{ $coupon->niveauRisqueLabel() }}</span>
                        </td>
                        <td style="color:var(--c-muted); font-size:0.85rem;">
                            {{ $coupon->selections->count() ?: '—' }}
                        </td>
                        <td>
                            <div style="display:flex; gap:4px; flex-wrap:wrap;">
                                @foreach($coupon->codes as $code)
                                    <span class="pill pill-gray" title="{{ $code->code }}">{{ $code->bookmakerLabel() }}</span>
                                @endforeach
                                @if($coupon->codes->isEmpty())
                                    <span style="color:var(--c-muted); font-size:0.8rem;">—</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="pill {{ $coupon->isPublie() ? 'pill-green' : 'pill-gray' }}">
                                {{ $coupon->isPublie() ? 'Publié' : 'Dépublié' }}
                            </span>
                        </td>
                        <td>
                            <span class="pill {{ match($coupon->statut_resultat) {
                                'gagne'  => 'pill-green',
                                'perdu'  => 'pill-red',
                                'annule' => 'pill-gray',
                                default  => 'pill-yellow',
                            } }}">
                                {{ match($coupon->statut_resultat) {
                                    'gagne'  => 'Gagné',
                                    'perdu'  => 'Perdu',
                                    'annule' => 'Annulé',
                                    default  => 'En cours',
                                } }}
                            </span>
                        </td>
                        <td style="color:var(--c-muted); font-size:0.82rem; white-space:nowrap;">
                            {{ $coupon->created_at->format('d/m/Y') }}
                        </td>
                        <td>
                            <div style="display:flex; gap:6px; flex-wrap:wrap;">
                                <button wire:click="editer({{ $coupon->id }})" class="btn-sm-outline">Éditer</button>
                                @if($coupon->isPublie())
                                    <button wire:click="depublier({{ $coupon->id }})" wire:confirm="Dépublier ce coupon ?" class="btn-sm-outline">Dépublier</button>
                                @else
                                    <button wire:click="publier({{ $coupon->id }})" wire:confirm="Publier ce coupon ?" class="btn-sm-green">Publier</button>
                                @endif
                                <button wire:click="ouvrirResultat({{ $coupon->id }})" class="btn-sm-outline" style="color:var(--c-gold); border-color:rgba(255,214,0,0.3);">Résultat</button>
                                <button wire:click="ouvrirCapture({{ $coupon->id }})" class="btn-sm-outline" style="color:var(--c-gold); border-color:rgba(255,214,0,0.3);">
                                    {{ $coupon->capture_gagnant ? '📷' : '📷 +' }}
                                </button>
                                <button wire:click="supprimer({{ $coupon->id }})" wire:confirm="Supprimer définitivement ce coupon ?" class="btn-sm-red">✕</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:3rem; color:var(--c-muted); font-family:var(--font-display); letter-spacing:0.06em; text-transform:uppercase; font-size:0.85rem;">
                            Aucun coupon trouvé
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── CARTES MOBILES ───────────────────────────────────── --}}
    <div class="coupons-cards-wrap">
        @forelse($coupons as $coupon)
            <div style="background:var(--c-bg2); border:1px solid var(--c-border); padding:1rem; margin-bottom:0.75rem;">

                {{-- Ligne 1 : titre + résultat --}}
                <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:0.5rem; margin-bottom:0.6rem;">
                    <span style="font-family:var(--font-display); font-weight:700; font-size:0.95rem; line-height:1.3;">
                        {{ $coupon->titre }}
                    </span>
                    <span class="pill {{ match($coupon->statut_resultat) {
                        'gagne'  => 'pill-green',
                        'perdu'  => 'pill-red',
                        'annule' => 'pill-gray',
                        default  => 'pill-yellow',
                    } }}" style="flex-shrink:0;">
                        {{ match($coupon->statut_resultat) {
                            'gagne'  => 'Gagné',
                            'perdu'  => 'Perdu',
                            'annule' => 'Annulé',
                            default  => 'En cours',
                        } }}
                    </span>
                </div>

                {{-- Ligne 2 : risque + publication + sélections + date --}}
                <div style="display:flex; gap:0.75rem; flex-wrap:wrap; margin-bottom:0.75rem; align-items:center;">
                    <span class="pill {{ match($coupon->niveau_risque) {
                        'faible' => 'pill-green',
                        'modere' => 'pill-yellow',
                        'risque' => 'pill-red',
                        default  => 'pill-gray',
                    } }}">{{ $coupon->niveauRisqueLabel() }}</span>

                    <span class="pill {{ $coupon->isPublie() ? 'pill-green' : 'pill-gray' }}">
                        {{ $coupon->isPublie() ? 'Publié' : 'Dépublié' }}
                    </span>

                    @if($coupon->selections->count())
                        <span style="font-family:var(--font-display); font-size:0.75rem; color:var(--c-muted);">
                            {{ $coupon->selections->count() }} sél.
                        </span>
                    @endif

                    <span style="font-family:var(--font-display); font-size:0.75rem; color:var(--c-muted); margin-left:auto;">
                        {{ $coupon->created_at->format('d/m/Y') }}
                    </span>
                </div>

                {{-- Bookmakers --}}
                @if($coupon->codes->isNotEmpty())
                    <div style="display:flex; gap:4px; flex-wrap:wrap; margin-bottom:0.75rem;">
                        @foreach($coupon->codes as $code)
                            <span class="pill pill-gray" title="{{ $code->code }}">{{ $code->bookmakerLabel() }}</span>
                        @endforeach
                    </div>
                @endif

                {{-- Actions --}}
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.5rem;">
                    <button wire:click="editer({{ $coupon->id }})" class="btn-sm-outline" style="justify-content:center;">Éditer</button>
                    <button wire:click="ouvrirResultat({{ $coupon->id }})" class="btn-sm-outline" style="justify-content:center; color:var(--c-gold); border-color:rgba(255,214,0,0.3);">Résultat</button>
                    <button wire:click="ouvrirCapture({{ $coupon->id }})" class="btn-sm-outline"
                            style="justify-content:center; color:var(--c-gold); border-color:rgba(255,214,0,0.3);">
                        {{ $coupon->capture_gagnant ? '📷 Capture' : '📷 Ajouter' }}
                    </button>
                    @if($coupon->isPublie())
                        <button wire:click="depublier({{ $coupon->id }})" wire:confirm="Dépublier ce coupon ?" class="btn-sm-outline" style="justify-content:center;">Dépublier</button>
                    @else
                        <button wire:click="publier({{ $coupon->id }})" wire:confirm="Publier ce coupon ?" class="btn-sm-green" style="justify-content:center;">Publier</button>
                    @endif
                    <button wire:click="supprimer({{ $coupon->id }})" wire:confirm="Supprimer définitivement ce coupon ?" class="btn-sm-red" style="justify-content:center; grid-column:span 2;">✕ Supprimer</button>
                </div>

            </div>
        @empty
            <div style="text-align:center; padding:3rem; color:var(--c-muted); font-family:var(--font-display); letter-spacing:0.06em; text-transform:uppercase; font-size:0.85rem;">
                Aucun coupon trouvé
            </div>
        @endforelse
    </div>

    {{-- ── MODAL CRÉATION / ÉDITION ────────────────────────── --}}
    @if($showModal)
    <div style="position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:200; display:flex; align-items:flex-start; justify-content:center; padding:1rem; overflow-y:auto;">
        <div style="background:var(--c-bg2); border:1px solid var(--c-border-g); width:100%; max-width:760px; flex-shrink:0; margin:auto;">

            <div style="display:flex; align-items:center; justify-content:space-between; padding:1.25rem 1.5rem; border-bottom:1px solid var(--c-border);">
                <span style="font-family:var(--font-display); font-weight:700; font-size:1.1rem; letter-spacing:0.06em; text-transform:uppercase;">
                    {{ $modeEdition ? 'Modifier le coupon' : 'Nouveau coupon' }}
                </span>
                <button wire:click="$set('showModal', false)"
                        style="background:none; border:none; color:var(--c-muted); font-size:1.2rem; cursor:pointer; padding:4px 8px; line-height:1;">✕</button>
            </div>

            <div style="padding:1.5rem;">

                @if($errors->any())
                    <div class="flash-error" style="margin-bottom:1rem;">
                        @foreach($errors->all() as $error)<div>✕ {{ $error }}</div>@endforeach
                    </div>
                @endif

                <div style="margin-bottom:1rem;">
                    <label style="display:block; font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.4rem;">Titre *</label>
                    <input wire:model="titre" type="text" placeholder="Ex: Coupon du jour — Ligue 1"
                           style="width:100%; background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:9px 12px; font-family:var(--font-body); font-size:0.9rem; outline:none;">
                </div>

                <div style="margin-bottom:1rem;">
                    <label style="display:block; font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.4rem;">Niveau de risque *</label>
                    <select wire:model="niveau_risque"
                            style="width:100%; background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:9px 12px; font-family:var(--font-display); font-size:0.85rem; letter-spacing:0.05em; outline:none; cursor:pointer;">
                        <option value="faible">Faible risque</option>
                        <option value="modere">Risque modéré</option>
                        <option value="risque">Risqué</option>
                    </select>
                </div>

                <div style="margin-bottom:1rem;">
                    <label style="display:block; font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.4rem;">Description courte</label>
                    <textarea wire:model="description" rows="2" placeholder="Résumé visible sur la liste des coupons…"
                              style="width:100%; background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:9px 12px; font-family:var(--font-body); font-size:0.9rem; outline:none; resize:vertical;"></textarea>
                </div>

                <div style="margin-bottom:1.5rem;">
                    <label style="display:block; font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.4rem;">Analyse détaillée</label>
                    <textarea wire:model="analyse" rows="4" placeholder="Analyse complète réservée aux abonnés…"
                              style="width:100%; background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:9px 12px; font-family:var(--font-body); font-size:0.9rem; outline:none; resize:vertical;"></textarea>
                </div>

                <div style="margin-bottom:1.5rem;">
                    <label style="display:block; font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.4rem;">
                        Capture d'écran <span style="color:var(--c-border); font-weight:400;">(optionnel)</span>
                    </label>

                    <input wire:model="capture_gagnant" type="file" accept="image/*"
                        style="width:100%; background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-muted); padding:8px 12px; font-family:var(--font-body); font-size:0.85rem; cursor:pointer;">

                    @error('capture_gagnant')
                        <div style="color:#ef4444; font-size:0.78rem; margin-top:0.3rem;">✕ {{ $message }}</div>
                    @enderror

                    {{-- Aperçu : image déjà enregistrée en mode édition --}}
                    @if($modeEdition && $couponId && !$capture_gagnant)
                        @php $couponEnCours = \App\Models\Coupon::find($couponId); @endphp
                        @if($couponEnCours?->capture_gagnant)
                            <div style="margin-top:0.6rem;">
                                <div style="font-family:var(--font-display); font-size:0.68rem; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.3rem;">Capture actuelle</div>
                                <img src="{{ Storage::url($couponEnCours->capture_gagnant) }}"
                                    style="max-height:120px; border:1px solid var(--c-border); object-fit:contain;">
                            </div>
                        @endif
                    @endif

                    {{-- Aperçu : nouveau fichier sélectionné --}}
                    @if($capture_gagnant && is_object($capture_gagnant))
                        <div style="margin-top:0.6rem;">
                            <div style="font-family:var(--font-display); font-size:0.68rem; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.3rem;">Aperçu</div>
                            <img src="{{ $capture_gagnant->temporaryUrl() }}"
                                style="max-height:120px; border:1px solid var(--c-border); object-fit:contain;">
                        </div>
                    @endif
                </div>

                <div style="height:1px; background:var(--c-border); margin-bottom:1.5rem;"></div>

                {{-- Codes bookmakers --}}
                <div style="margin-bottom:1.5rem;">
                    <div style="font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.75rem;">
                        Codes par bookmaker
                    </div>
                    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(160px, 1fr)); gap:0.75rem;">
                        @foreach(['1xbet' => '1xBet', 'betwinner' => 'BetWinner', 'melbet' => 'Melbet', '1win' => '1Win'] as $key => $label)
                            <div>
                                <label style="display:block; font-family:var(--font-display); font-size:0.7rem; font-weight:600; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.3rem;">{{ $label }}</label>
                                <input wire:model="codes.{{ $key }}" type="text" placeholder="Code coupon…"
                                       style="width:100%; background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:7px 10px; font-family:var(--font-body); font-size:0.85rem; outline:none;">
                            </div>
                        @endforeach
                    </div>
                </div>

                <div style="height:1px; background:var(--c-border); margin-bottom:1.5rem;"></div>

                {{-- Sélections --}}
                <div style="margin-bottom:1.5rem;">
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.75rem;">
                        <div style="font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted);">
                            Sélections <span style="color:var(--c-border); font-weight:400;">(optionnel)</span>
                        </div>
                        <button wire:click="ajouterSelection" class="btn-sm-outline" style="font-size:0.7rem;">+ Ajouter</button>
                    </div>

                    @if(count($selections) === 0)
                        <div style="text-align:center; padding:1.5rem; border:1px dashed var(--c-border); color:var(--c-muted); font-size:0.82rem; font-family:var(--font-display); letter-spacing:0.06em; text-transform:uppercase;">
                            Aucune sélection — coupon sans détail de match
                        </div>
                    @endif

                    @foreach($selections as $i => $sel)
                        <div style="background:var(--c-bg3); border:1px solid var(--c-border); padding:1rem; margin-bottom:0.75rem;">

                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.75rem;">
                                <span style="font-family:var(--font-display); font-size:0.75rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-green);">
                                    Sélection {{ $i + 1 }}
                                </span>
                                <button wire:click="supprimerSelection({{ $i }})" class="btn-sm-red" style="padding:3px 10px;">✕</button>
                            </div>

                            {{-- Équipes : 1 col sur mobile, 2 col sinon --}}
                            <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(140px, 1fr)); gap:0.6rem; margin-bottom:0.6rem;">
                                <div>
                                    <label style="display:block; font-family:var(--font-display); font-size:0.68rem; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.2rem;">Domicile</label>
                                    <input wire:model="selections.{{ $i }}.equipe_domicile" type="text" placeholder="PSG"
                                           style="width:100%; background:var(--c-bg2); border:1px solid var(--c-border); color:var(--c-text); padding:6px 10px; font-size:0.85rem; outline:none;">
                                </div>
                                <div>
                                    <label style="display:block; font-family:var(--font-display); font-size:0.68rem; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.2rem;">Extérieur</label>
                                    <input wire:model="selections.{{ $i }}.equipe_exterieur" type="text" placeholder="OM"
                                           style="width:100%; background:var(--c-bg2); border:1px solid var(--c-border); color:var(--c-text); padding:6px 10px; font-size:0.85rem; outline:none;">
                                </div>
                            </div>

                            {{-- Compétition / Date / Cote --}}
                            <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(120px, 1fr)); gap:0.6rem; margin-bottom:0.6rem;">
                                <div>
                                    <label style="display:block; font-family:var(--font-display); font-size:0.68rem; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.2rem;">Compétition</label>
                                    <input wire:model="selections.{{ $i }}.competition" type="text" placeholder="Ligue 1"
                                           style="width:100%; background:var(--c-bg2); border:1px solid var(--c-border); color:var(--c-text); padding:6px 10px; font-size:0.85rem; outline:none;">
                                </div>
                                <div>
                                    <label style="display:block; font-family:var(--font-display); font-size:0.68rem; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.2rem;">Date & heure</label>
                                    <input wire:model="selections.{{ $i }}.date_match" type="datetime-local"
                                           style="width:100%; background:var(--c-bg2); border:1px solid var(--c-border); color:var(--c-text); padding:6px 10px; font-size:0.85rem; outline:none;">
                                </div>
                                <div>
                                    <label style="display:block; font-family:var(--font-display); font-size:0.68rem; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.2rem;">Cote</label>
                                    <input wire:model="selections.{{ $i }}.cote" type="number" step="0.01" min="1" placeholder="1.85"
                                           style="width:100%; background:var(--c-bg2); border:1px solid var(--c-border); color:var(--c-text); padding:6px 10px; font-size:0.85rem; outline:none;">
                                </div>
                            </div>

                            <div>
                                <label style="display:block; font-family:var(--font-display); font-size:0.68rem; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.2rem;">Type de pari</label>
                                <input wire:model="selections.{{ $i }}.type_pari" type="text" placeholder="1X2, Over 2.5, BTTS…"
                                       style="width:100%; background:var(--c-bg2); border:1px solid var(--c-border); color:var(--c-text); padding:6px 10px; font-size:0.85rem; outline:none;">
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="display:flex; justify-content:flex-end; gap:0.75rem; padding-top:1rem; border-top:1px solid var(--c-border); flex-wrap:wrap;">
                    <button wire:click="$set('showModal', false)" class="btn-sm-outline">Annuler</button>
                    <button wire:click="sauvegarder" class="btn-sm-green">
                        <span wire:loading.remove wire:target="sauvegarder">{{ $modeEdition ? 'Enregistrer' : 'Créer le coupon' }}</span>
                        <span wire:loading wire:target="sauvegarder">…</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ── MODAL RÉSULTAT ──────────────────────────────────── --}}
    @if($showResultatModal)
    <div style="position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:200; display:flex; align-items:center; justify-content:center; padding:1rem;">
        <div style="background:var(--c-bg2); border:1px solid var(--c-border-g); width:100%; max-width:380px;">

            <div style="display:flex; align-items:center; justify-content:space-between; padding:1.25rem 1.5rem; border-bottom:1px solid var(--c-border);">
                <span style="font-family:var(--font-display); font-weight:700; font-size:1rem; letter-spacing:0.06em; text-transform:uppercase;">
                    Mettre à jour le résultat
                </span>
                <button wire:click="$set('showResultatModal', false)"
                        style="background:none; border:none; color:var(--c-muted); font-size:1.2rem; cursor:pointer; padding:4px 8px;">✕</button>
            </div>

            <div style="padding:1.5rem;">
                <label style="display:block; font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.5rem;">Résultat</label>
                <select wire:model="statut_resultat"
                        style="width:100%; background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:9px 12px; font-family:var(--font-display); font-size:0.85rem; letter-spacing:0.05em; outline:none; cursor:pointer; margin-bottom:1.25rem;">
                    <option value="en_cours">En cours</option>
                    <option value="gagne">Gagné ✓</option>
                    <option value="perdu">Perdu ✕</option>
                    <option value="annule">Annulé</option>
                </select>

                <div style="display:flex; justify-content:flex-end; gap:0.75rem;">
                    <button wire:click="$set('showResultatModal', false)" class="btn-sm-outline">Annuler</button>
                    <button wire:click="sauvegarderResultat" class="btn-sm-green">Confirmer</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ── MODAL CAPTURE D'ÉCRAN ───────────────────────────── --}}
    @if($showCaptureModal)
    <div style="position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:200; display:flex; align-items:center; justify-content:center; padding:1rem;">
        <div style="background:var(--c-bg2); border:1px solid var(--c-border-g); width:100%; max-width:420px;">

            <div style="display:flex; align-items:center; justify-content:space-between; padding:1.25rem 1.5rem; border-bottom:1px solid var(--c-border);">
                <span style="font-family:var(--font-display); font-weight:700; font-size:1rem; letter-spacing:0.06em; text-transform:uppercase;">
                    Capture d'écran
                </span>
                <button wire:click="$set('showCaptureModal', false)"
                        style="background:none; border:none; color:var(--c-muted); font-size:1.2rem; cursor:pointer; padding:4px 8px;">✕</button>
            </div>

            <div style="padding:1.5rem;">

                {{-- Capture existante --}}
                @if($captureGagnantId && \App\Models\Coupon::find($captureGagnantId)?->capture_gagnant)
                    <div style="margin-bottom:1rem;">
                        <div style="font-family:var(--font-display); font-size:0.68rem; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.4rem;">Capture actuelle</div>
                        <img src="{{ Storage::url(\App\Models\Coupon::find($captureGagnantId)->capture_gagnant) }}"
                            style="max-height:150px; border:1px solid var(--c-border); object-fit:contain;">
                    </div>
                @endif

                <div style="margin-bottom:1.25rem;">
                    <label style="display:block; font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.4rem;">
                        Nouvelle capture
                    </label>
                    <input wire:model="capture_gagnant_upload" type="file" accept="image/*"
                        style="width:100%; background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-muted); padding:8px 12px; font-family:var(--font-body); font-size:0.85rem; cursor:pointer;">

                    @error('capture_gagnant_upload')
                        <div style="color:#ef4444; font-size:0.78rem; margin-top:0.3rem;">✕ {{ $message }}</div>
                    @enderror

                    @if($capture_gagnant_upload)
                        <img src="{{ $capture_gagnant_upload->temporaryUrl() }}"
                            style="margin-top:0.6rem; max-height:150px; border:1px solid var(--c-border); object-fit:contain;">
                    @endif
                </div>

                <div style="display:flex; justify-content:flex-end; gap:0.75rem;">
                    <button wire:click="$set('showCaptureModal', false)" class="btn-sm-outline">Annuler</button>
                    <button wire:click="sauvegarderCapture" class="btn-sm-green">
                        <span wire:loading.remove wire:target="sauvegarderCapture">Enregistrer</span>
                        <span wire:loading wire:target="sauvegarderCapture">…</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

@push('scripts')
<style>
    .coupons-cards-wrap { display: none; }

    @media (max-width: 768px) {
        .coupons-table-wrap { display: none; }
        .coupons-cards-wrap { display: block; }
    }
</style>
@endpush