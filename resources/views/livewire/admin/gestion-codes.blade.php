{{-- resources/views/livewire/admin/gestion-codes.blade.php --}}
<div>

    {{-- ── GÉNÉRATION DE CODES ─────────────────────────────── --}}
    <div style="background:var(--c-bg2); border:1px solid var(--c-border-g); padding:1.5rem; margin-bottom:1.5rem;">

        <div style="font-family:var(--font-display); font-weight:700; font-size:0.85rem; letter-spacing:0.08em; text-transform:uppercase; color:var(--c-text); margin-bottom:1rem;">
            Générer des codes d'accès
        </div>

        {{-- Grille responsive --}}
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(140px, 1fr)); gap:1rem; align-items:flex-end;">

            {{-- Plan --}}
            <div>
                <label style="display:block; font-family:var(--font-display); font-size:0.7rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.4rem;">
                    Plan
                </label>
                <select wire:model.live="plan"
                        style="width:100%; background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:8px 12px; font-family:var(--font-display); font-size:0.85rem; letter-spacing:0.05em; outline:none; cursor:pointer;">
                    @foreach($plans as $p)
                        <option value="{{ $p['slug'] }}">{{ $p['nom'] }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Quantité --}}
            <div>
                <label style="display:block; font-family:var(--font-display); font-size:0.7rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.4rem;">
                    Quantité <span style="color:var(--c-muted); font-weight:400;">(max 20)</span>
                </label>
                <input wire:model="quantite" type="number" min="1" max="20"
                    style="width:100%; background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:8px 12px; font-family:var(--font-body); font-size:0.9rem; outline:none;">
            </div>

            {{-- Type --}}
            <div>
                <label style="display:block; font-family:var(--font-display); font-size:0.7rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.4rem;">
                    Type
                </label>
                <div style="display:flex; gap:0.5rem;">
                    <button type="button" wire:click="$set('estPayant', false)"
                            style="flex:1; padding:7px 10px; font-family:var(--font-display); font-size:0.78rem; font-weight:700; letter-spacing:0.07em; text-transform:uppercase; border:1px solid var(--c-border); cursor:pointer; transition:all .15s;
                                background:{{ !$estPayant ? 'var(--c-green)' : 'var(--c-bg3)' }};
                                color:{{ !$estPayant ? '#000' : 'var(--c-muted)' }};">
                        Gratuit
                    </button>
                    <button type="button" wire:click="$set('estPayant', true)"
                            style="flex:1; padding:7px 10px; font-family:var(--font-display); font-size:0.78rem; font-weight:700; letter-spacing:0.07em; text-transform:uppercase; border:1px solid var(--c-border); cursor:pointer; transition:all .15s;
                                background:{{ $estPayant ? 'var(--c-gold)' : 'var(--c-bg3)' }};
                                color:{{ $estPayant ? '#000' : 'var(--c-muted)' }};">
                        Payant
                    </button>
                </div>
            </div>

            {{-- Montant si payant --}}
            @if($estPayant && $planActuel)
                <div>
                    <label style="display:block; font-family:var(--font-display); font-size:0.7rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.4rem;">
                        Montant
                    </label>
                    <div style="padding:8px 16px; background:var(--c-bg3); border:1px solid rgba(255,214,0,0.3); font-family:var(--font-display); font-weight:700; font-size:0.9rem; color:var(--c-gold); letter-spacing:0.06em; white-space:nowrap;">
                        {{ number_format($planActuel['prix'], 0, ',', ' ') }} XOF
                    </div>
                </div>
            @endif

            {{-- Bouton --}}
            <div>
                <button wire:click="generer" class="btn-sm-green" style="width:100%; padding:9px 20px; justify-content:center;">
                    <span wire:loading.remove wire:target="generer">⚡ Générer</span>
                    <span wire:loading wire:target="generer">…</span>
                </button>
            </div>

        </div>

        {{-- Erreurs --}}
        @if($errors->any())
            <div class="flash-error" style="margin-top:1rem;">
                @foreach($errors->all() as $error) <div>✕ {{ $error }}</div> @endforeach
            </div>
        @endif

        {{-- Codes générés --}}
        @if(count($derniersCodesGeneres) > 0)
            <div style="margin-top:1.25rem; padding:1rem; background:var(--c-bg3); border:1px solid rgba(0,230,118,0.2);">
                <div style="font-family:var(--font-display); font-size:0.7rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-green); margin-bottom:0.75rem;">
                    ✓ {{ count($derniersCodesGeneres) }} code(s) {{ $estPayant ? 'payant(s)' : 'gratuit(s)' }} généré(s) — à envoyer via WhatsApp
                </div>
                <div style="display:flex; flex-wrap:wrap; gap:0.5rem;">
                    @foreach($derniersCodesGeneres as $code)
                        <div x-data="{ copie: false }" style="display:flex; align-items:center; gap:0.4rem; flex-wrap:wrap;">
                            <span style="font-family:monospace; font-size:0.95rem; font-weight:700; color:var(--c-gold); background:rgba(255,214,0,0.08); border:1px solid rgba(255,214,0,0.2); padding:4px 12px; letter-spacing:0.08em;">
                                {{ $code }}
                            </span>
                            <button type="button"
                                    @click="navigator.clipboard.writeText('{{ $code }}'); copie = true; setTimeout(() => copie = false, 2000)"
                                    style="background:none; border:1px solid var(--c-border); padding:4px 10px; cursor:pointer; font-family:var(--font-display); font-size:0.68rem; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; transition:all .15s;"
                                    :style="copie ? 'color:var(--c-green); border-color:var(--c-green);' : 'color:var(--c-muted); border-color:var(--c-border);'">
                                <span x-show="!copie">Copier</span>
                                <span x-show="copie">✓ Copié</span>
                            </button>
                        </div>
                    @endforeach
                </div>

                @if(count($derniersCodesGeneres) > 1)
                    <div x-data="{ toutCopie: false }" style="margin-top:0.75rem;">
                        <button type="button"
                                @click="navigator.clipboard.writeText('{{ implode('\n', $derniersCodesGeneres) }}'); toutCopie = true; setTimeout(() => toutCopie = false, 2000)"
                                style="background:none; border:1px solid var(--c-border); padding:5px 14px; cursor:pointer; font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.07em; text-transform:uppercase; transition:all .15s;"
                                :style="toutCopie ? 'color:var(--c-green); border-color:var(--c-green);' : 'color:var(--c-muted); border-color:var(--c-border);'">
                            <span x-show="!toutCopie">📋 Tout copier</span>
                            <span x-show="toutCopie">✓ Tout copié</span>
                        </button>
                    </div>
                @endif
            </div>
        @endif

    </div>

    {{-- ── BARRE D'OUTILS ─────────────────────────────────── --}}
    <div style="display:flex; align-items:center; justify-content:space-between; gap:0.75rem; margin-bottom:1rem; flex-wrap:wrap;">

        <div style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:center; flex:1; min-width:0;">

            <input wire:model.live="recherche" type="text" placeholder="Code ou utilisateur…"
                   style="background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:7px 12px; font-family:var(--font-body); font-size:0.85rem; min-width:0; flex:1; max-width:220px; outline:none;">

            <select wire:model.live="filtreStatut"
                    style="background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:7px 12px; font-family:var(--font-display); font-size:0.8rem; letter-spacing:0.05em; text-transform:uppercase; outline:none; cursor:pointer; flex:1; min-width:130px;">
                <option value="">Tous statuts</option>
                <option value="actif">Actif</option>
                <option value="utilise">Utilisé</option>
                <option value="revoque">Révoqué</option>
            </select>

            <select wire:model.live="filtrePlan"
                    style="background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:7px 12px; font-family:var(--font-display); font-size:0.8rem; letter-spacing:0.05em; text-transform:uppercase; outline:none; cursor:pointer; flex:1; min-width:130px;">
                <option value="">Tous plans</option>
                <option value="hebdomadaire">Hebdomadaire</option>
                <option value="mensuel">Mensuel</option>
                <option value="premium">Premium</option>
            </select>

        </div>

        <div style="font-family:var(--font-display); font-size:0.8rem; letter-spacing:0.06em; text-transform:uppercase; color:var(--c-muted); white-space:nowrap;">
            {{ $codes->count() }} code(s)
        </div>
    </div>

    {{-- ── TABLEAU desktop ─────────────────────────────────── --}}
    <div class="codes-table-wrap" style="background:var(--c-bg2); border:1px solid var(--c-border); overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Plan</th>
                    <th>Statut</th>
                    <th>Utilisateur</th>
                    <th>Généré par</th>
                    <th>Expire le</th>
                    <th>Créé le</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($codes as $code)
                    <tr>
                        <td>
                            <div x-data="{ copie: false }" style="display:flex; align-items:center; gap:0.5rem;">
                                <span style="font-family:monospace; font-size:0.9rem; font-weight:700; color:var(--c-gold); letter-spacing:0.06em;">
                                    {{ $code->code }}
                                </span>
                                <button type="button"
                                        @click="navigator.clipboard.writeText('{{ $code->code }}'); copie = true; setTimeout(() => copie = false, 2000)"
                                        style="background:none; border:1px solid var(--c-border); padding:3px 8px; cursor:pointer; font-family:var(--font-display); font-size:0.65rem; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; transition:all .15s; white-space:nowrap;"
                                        :style="copie ? 'color:var(--c-green); border-color:var(--c-green);' : 'color:var(--c-muted); border-color:var(--c-border);'">
                                    <span x-show="!copie">Copier</span>
                                    <span x-show="copie">✓ Copié</span>
                                </button>
                            </div>
                        </td>
                        <td>
                            <span class="pill pill-gold" style="text-transform:capitalize;">{{ $code->plan }}</span>
                        </td>
                        <td>
                            <span class="pill {{ match($code->statut) {
                                'actif'   => $code->isValide() ? 'pill-green' : 'pill-yellow',
                                'utilise' => 'pill-gray',
                                'revoque' => 'pill-red',
                                default   => 'pill-gray',
                            } }}">
                                {{ match($code->statut) {
                                    'actif'   => $code->isValide() ? 'Actif' : 'Expiré',
                                    'utilise' => 'Utilisé',
                                    'revoque' => 'Révoqué',
                                    default   => $code->statut,
                                } }}
                            </span>
                        </td>
                        <td style="color:var(--c-muted); font-size:0.85rem;">
                            @if($code->user)
                                <div style="font-family:var(--font-display); font-weight:600; color:var(--c-text); font-size:0.88rem;">{{ $code->user->nom ?? '—' }}</div>
                                <div style="font-size:0.78rem;">{{ $code->user->telephone }}</div>
                            @else
                                <span style="color:var(--c-muted);">Non attribué</span>
                            @endif
                        </td>
                        <td style="color:var(--c-muted); font-size:0.82rem;">
                            {{ $code->generePar->nom ?? $code->generePar->telephone ?? '—' }}
                        </td>
                        <td style="font-size:0.82rem; white-space:nowrap; {{ !$code->isValide() && $code->statut === 'actif' ? 'color:var(--c-danger)' : 'color:var(--c-muted)' }}">
                            {{ $code->expire_le?->format('d/m/Y H:i') ?? '—' }}
                        </td>
                        <td style="color:var(--c-muted); font-size:0.82rem; white-space:nowrap;">
                            {{ $code->created_at->format('d/m/Y') }}
                        </td>
                        <td>
                            @if($code->statut === 'actif' && $code->isValide())
                                <button wire:click="revoquer({{ $code->id }})"
                                        wire:confirm="Révoquer ce code ?"
                                        class="btn-sm-red">
                                    Révoquer
                                </button>
                            @else
                                <span style="color:var(--c-muted); font-size:0.78rem; font-family:var(--font-display); letter-spacing:0.05em; text-transform:uppercase;">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:3rem; color:var(--c-muted); font-family:var(--font-display); letter-spacing:0.06em; text-transform:uppercase; font-size:0.85rem;">
                            Aucun code trouvé
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── CARTES MOBILES (< 768px) ────────────────────────── --}}
    <div class="codes-cards-wrap">
        @forelse($codes as $code)
            <div style="background:var(--c-bg2); border:1px solid var(--c-border); padding:1rem; margin-bottom:0.75rem;">

                {{-- Ligne 1 : code + statut --}}
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.6rem;">
                    <div x-data="{ copie: false }" style="display:flex; align-items:center; gap:0.5rem;">
    <span style="font-family:monospace; font-size:1rem; font-weight:700; color:var(--c-gold); letter-spacing:0.08em;">
        {{ $code->code }}
    </span>
    <button type="button"
            @click="navigator.clipboard.writeText('{{ $code->code }}'); copie = true; setTimeout(() => copie = false, 2000)"
            style="background:none; border:1px solid var(--c-border); padding:3px 8px; cursor:pointer; font-family:var(--font-display); font-size:0.65rem; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; transition:all .15s;"
            :style="copie ? 'color:var(--c-green); border-color:var(--c-green);' : 'color:var(--c-muted); border-color:var(--c-border);'">
        <span x-show="!copie">Copier</span>
        <span x-show="copie">✓ Copié</span>
    </button>
</div>
                    <span class="pill {{ match($code->statut) {
                        'actif'   => $code->isValide() ? 'pill-green' : 'pill-yellow',
                        'utilise' => 'pill-gray',
                        'revoque' => 'pill-red',
                        default   => 'pill-gray',
                    } }}">
                        {{ match($code->statut) {
                            'actif'   => $code->isValide() ? 'Actif' : 'Expiré',
                            'utilise' => 'Utilisé',
                            'revoque' => 'Révoqué',
                            default   => $code->statut,
                        } }}
                    </span>
                </div>

                {{-- Ligne 2 : plan + utilisateur --}}
                <div style="display:flex; gap:1rem; flex-wrap:wrap; margin-bottom:0.5rem;">
                    <div>
                        <div style="font-family:var(--font-display); font-size:0.65rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:2px;">Plan</div>
                        <span class="pill pill-gold" style="text-transform:capitalize;">{{ $code->plan }}</span>
                    </div>
                    <div>
                        <div style="font-family:var(--font-display); font-size:0.65rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:2px;">Utilisateur</div>
                        @if($code->user)
                            <div style="font-family:var(--font-display); font-weight:600; color:var(--c-text); font-size:0.85rem;">{{ $code->user->nom ?? '—' }}</div>
                            <div style="font-size:0.78rem; color:var(--c-muted);">{{ $code->user->telephone }}</div>
                        @else
                            <span style="color:var(--c-muted); font-size:0.82rem;">Non attribué</span>
                        @endif
                    </div>
                </div>

                {{-- Ligne 3 : dates + généré par --}}
                <div style="display:flex; gap:1rem; flex-wrap:wrap; margin-bottom:0.75rem;">
                    <div>
                        <div style="font-family:var(--font-display); font-size:0.65rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:2px;">Expire</div>
                        <span style="font-size:0.82rem; {{ !$code->isValide() && $code->statut === 'actif' ? 'color:var(--c-danger)' : 'color:var(--c-muted)' }}">
                            {{ $code->expire_le?->format('d/m/Y') ?? '—' }}
                        </span>
                    </div>
                    <div>
                        <div style="font-family:var(--font-display); font-size:0.65rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:2px;">Créé le</div>
                        <span style="font-size:0.82rem; color:var(--c-muted);">{{ $code->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div>
                        <div style="font-family:var(--font-display); font-size:0.65rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:2px;">Par</div>
                        <span style="font-size:0.82rem; color:var(--c-muted);">{{ $code->generePar->nom ?? $code->generePar->telephone ?? '—' }}</span>
                    </div>
                </div>

                {{-- Action --}}
                @if($code->statut === 'actif' && $code->isValide())
                    <button wire:click="revoquer({{ $code->id }})"
                            wire:confirm="Révoquer ce code ?"
                            class="btn-sm-red" style="width:100%; justify-content:center;">
                        Révoquer
                    </button>
                @endif

            </div>
        @empty
            <div style="text-align:center; padding:3rem; color:var(--c-muted); font-family:var(--font-display); letter-spacing:0.06em; text-transform:uppercase; font-size:0.85rem;">
                Aucun code trouvé
            </div>
        @endforelse
    </div>

</div>

{{-- ── Styles responsive ──────────────────────────────── --}}
@push('scripts')
<style>
    .codes-cards-wrap { display: none; }

    @media (max-width: 768px) {
        .codes-table-wrap { display: none; }
        .codes-cards-wrap { display: block; }
    }
</style>
@endpush