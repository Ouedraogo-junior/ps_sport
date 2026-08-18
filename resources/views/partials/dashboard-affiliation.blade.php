{{--
    Partial : bloc affiliation
    Variables requises : $referralCode, $nombreFilleuls, $soldeAffiliation
--}}

@if(auth()->user()->referralCode || auth()->user()->abonnementActif)

<div style="background:var(--c-bg2); border:1px solid var(--c-border); padding:1.5rem; margin-top:1.5rem;">

    {{-- En-tête --}}
    <div style="font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.12em; text-transform:uppercase; color:var(--c-muted); margin-bottom:1.25rem;">
        🔗 Parrainage
    </div>

    @if($referralCode)

        {{-- Code + lien copiable --}}
        <div style="margin-bottom:1.25rem;">
            <div style="font-size:0.78rem; color:var(--c-muted); margin-bottom:0.5rem;">
                Partagez votre code pour gagner des bonus à chaque filleul.
            </div>

            <div style="display:flex; align-items:center; gap:0; margin-bottom:0.75rem;">
                <div style="flex:1; background:var(--c-bg3); border:1px solid var(--c-border-g); padding:11px 14px; font-family:monospace; font-size:1.05rem; font-weight:700; letter-spacing:0.1em; color:var(--c-green);">
                    {{ $referralCode->code }}
                </div>
                <button onclick="copierCode('{{ $referralCode->code }}')"
                    style="background:var(--c-green); color:#000; border:none; padding:11px 16px; font-family:var(--font-display); font-weight:700; font-size:0.75rem; letter-spacing:0.07em; text-transform:uppercase; cursor:pointer; white-space:nowrap; transition:background 0.2s; flex-shrink:0;"
                    onmouseover="this.style.background='var(--c-green-dim)'"
                    onmouseout="this.style.background='var(--c-green)'"
                    id="btn-copier-code">
                    Copier
                </button>
            </div>

            {{-- Lien direct --}}
            <div x-data="{ ouvert: false }">
                <button @click="ouvert = !ouvert"
                    style="background:none; border:none; color:var(--c-muted); font-size:0.78rem; font-family:var(--font-display); font-weight:600; letter-spacing:0.05em; text-transform:uppercase; cursor:pointer; padding:0; text-decoration:underline;">
                    <span x-text="ouvert ? 'Masquer le lien' : 'Afficher le lien de parrainage'"></span>
                </button>
                <div x-show="ouvert" x-transition style="margin-top:0.5rem;">
                    <div style="display:flex; align-items:center; gap:0;">
                        <div style="flex:1; background:var(--c-bg3); border:1px solid var(--c-border); padding:9px 12px; font-size:0.75rem; color:var(--c-muted); font-family:monospace; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                            {{ url('/inscription') }}?ref={{ $referralCode->code }}
                        </div>
                        <button onclick="copierLien('{{ url('/inscription') }}?ref={{ $referralCode->code }}')"
                            style="background:var(--c-bg3); color:var(--c-muted); border:1px solid var(--c-border); border-left:none; padding:9px 14px; font-family:var(--font-display); font-weight:700; font-size:0.72rem; letter-spacing:0.07em; text-transform:uppercase; cursor:pointer; white-space:nowrap; transition:all 0.2s; flex-shrink:0;"
                            onmouseover="this.style.color='var(--c-text)'"
                            onmouseout="this.style.color='var(--c-muted)'"
                            id="btn-copier-lien">
                            Copier
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats : 3 blocs séparés --}}
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:0.75rem; margin-bottom:1.25rem;">
            <div style="background:var(--c-bg3); border:1px solid var(--c-border); padding:1rem;">
                <div style="font-size:0.72rem; color:var(--c-muted); margin-bottom:0.4rem; text-transform:uppercase; letter-spacing:0.08em; font-family:var(--font-display); font-weight:700;">
                    Filleuls actifs
                </div>
                <div style="font-family:var(--font-display); font-size:1.4rem; font-weight:800; color:var(--c-text);">
                    {{ $nombreFilleuls }}
                </div>
            </div>
            <div style="background:var(--c-bg3); border:1px solid var(--c-border); padding:1rem;">
                <div style="font-size:0.72rem; color:var(--c-muted); margin-bottom:0.4rem; text-transform:uppercase; letter-spacing:0.08em; font-family:var(--font-display); font-weight:700;">
                    Solde affiliation
                </div>
                <div style="font-family:var(--font-display); font-size:1.4rem; font-weight:800; color:var(--c-green);">
                    {{ number_format($soldeAffiliation->solde, 0, ',', ' ') }}
                    <span style="font-size:0.75rem;">FCFA</span>
                </div>
            </div>
            <div style="background:var(--c-bg3); border:1px solid var(--c-border); padding:1rem;">
                <div style="font-size:0.72rem; color:var(--c-muted); margin-bottom:0.4rem; text-transform:uppercase; letter-spacing:0.08em; font-family:var(--font-display); font-weight:700;">
                    Total cumulé
                </div>
                <div style="font-family:var(--font-display); font-size:1.4rem; font-weight:800; color:var(--c-gold);">
                    {{ number_format($soldeAffiliation->total_cumule, 0, ',', ' ') }}
                    <span style="font-size:0.75rem;">FCFA</span>
                </div>
            </div>
        </div>

        {{-- Retrait affiliation --}}
        @if($soldeAffiliation->solde > 0)
        <div style="border-top:1px solid var(--c-border); padding-top:1.25rem; margin-top:1.25rem;">

            @if($demandeAffiliationEnAttente)
                <div style="background:rgba(255,193,7,0.08); border:1px solid rgba(255,193,7,0.25); padding:1rem; margin-bottom:1.25rem;">
                    <div style="font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-warning); margin-bottom:0.5rem;">
                        ⏳ Retrait en cours de traitement
                    </div>
                    <div style="font-size:0.82rem; color:var(--c-muted); line-height:1.7;">
                        <div>Montant : <strong style="color:var(--c-text);">{{ $demandeAffiliationEnAttente->montantFormate() }}</strong></div>
                        <div>Opérateur : <strong style="color:var(--c-text);">{{ $demandeAffiliationEnAttente->operateurLabel() }}</strong></div>
                        <div>Soumis le : <strong style="color:var(--c-text);">{{ $demandeAffiliationEnAttente->created_at->format('d/m/Y à H:i') }}</strong></div>
                    </div>
                </div>

            @else
                <div x-data="{ ouvert: false }">

                    <button @click="ouvert = !ouvert"
                        style="width:100%; background:transparent; border:1px solid var(--c-green); color:var(--c-green); font-family:var(--font-display); font-weight:700; font-size:0.82rem; letter-spacing:0.08em; text-transform:uppercase; padding:11px; cursor:pointer; transition:all 0.2s;"
                        onmouseover="this.style.background='rgba(0,230,118,0.08)'"
                        onmouseout="this.style.background='transparent'">
                        <span x-text="ouvert ? '✕ Fermer' : '↑ Demander un retrait'"></span>
                    </button>

                    <div x-show="ouvert" x-transition style="border:1px solid var(--c-border); border-top:none; padding:1.25rem;">

                        @if($errors->any())
                            <div style="background:rgba(239,68,68,0.08); border:1px solid rgba(239,68,68,0.3); padding:0.75rem; font-size:0.82rem; color:var(--c-danger); margin-bottom:1rem; font-family:var(--font-display); font-weight:600;">
                                ✕ {{ $errors->first() }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('dashboard.retrait.affiliation') }}">
                            @csrf

                            <div style="margin-bottom:1rem;">
                                <label style="display:block; font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.5rem;">
                                    Montant (FCFA) *
                                </label>
                                <input type="number" name="montant"
                                    value="{{ old('montant') }}"
                                    min="1"
                                    max="{{ floor($soldeAffiliation->solde) }}"
                                    placeholder="Ex : {{ number_format($soldeAffiliation->solde, 0, ',', ' ') }}"
                                    style="width:100%; background:var(--c-bg3); border:1px solid var(--c-border-g); color:var(--c-text); padding:11px 14px; font-family:var(--font-display); font-size:1rem; font-weight:700; outline:none;">
                                <div style="font-size:0.75rem; color:var(--c-muted); margin-top:0.3rem;">
                                    Max disponible : {{ $soldeAffiliation->soldeFormate() }}
                                </div>
                            </div>

                            <div style="margin-bottom:1rem;">
                                <label style="display:block; font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.5rem;">
                                    Opérateur *
                                </label>
                                <div style="display:flex; flex-direction:column; gap:0.4rem;">
                                    @foreach([['orange', '🟠 Orange Money'], ['moov', '🔵 Moov Money'], ['wave', '🔷 Wave']] as [$val, $label])
                                        <label style="display:flex; align-items:center; gap:0.75rem; background:var(--c-bg3); border:1px solid var(--c-border); padding:0.7rem 1rem; cursor:pointer;">
                                            <input type="radio" name="operateur" value="{{ $val }}"
                                                {{ old('operateur') === $val ? 'checked' : '' }}
                                                style="accent-color:var(--c-green);">
                                            <span style="font-size:0.88rem;">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div style="margin-bottom:1.25rem;">
                                <label style="display:block; font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.5rem;">
                                    Numéro de réception *
                                </label>
                                <input type="text" name="numero_telephone"
                                    value="{{ old('numero_telephone', auth()->user()->telephone) }}"
                                    placeholder="Ex : 0700000000"
                                    maxlength="20"
                                    style="width:100%; background:var(--c-bg3); border:1px solid var(--c-border-g); color:var(--c-text); padding:11px 14px; font-family:var(--font-display); font-size:1rem; font-weight:700; outline:none;">
                            </div>

                            <button type="submit" class="btn-primary" style="width:100%; justify-content:center; padding:12px;">
                                Soumettre la demande →
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
        @endif

        {{-- Compteur depuis dernier retrait --}}
        @if($soldeAffiliation->filleuls_depuis_retrait > 0)
            <div style="background:rgba(0,230,118,0.05); border:1px solid rgba(0,230,118,0.2); padding:0.75rem 1rem; margin-bottom:1.25rem; font-size:0.82rem; color:var(--c-muted);">
                <span style="color:var(--c-green); font-family:var(--font-display); font-weight:700;">
                    {{ $soldeAffiliation->filleuls_depuis_retrait }}
                </span>
                filleul(s) depuis votre dernier retrait
            </div>
        @endif

        {{-- Historique filleuls --}}
        @if(auth()->user()->filleuls()->where('statut', 'credite')->exists())
            <div style="border-top:1px solid var(--c-border); padding-top:1rem;">
                <div style="font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.75rem;">
                    Derniers filleuls
                </div>
                @foreach(auth()->user()->filleuls()->with('filleul')->where('statut', 'credite')->latest()->take(5)->get() as $referral)
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:0.6rem 0; border-bottom:1px solid var(--c-border); gap:0.5rem; flex-wrap:wrap;">
                        <div>
                            <div style="font-family:var(--font-display); font-weight:600; font-size:0.88rem;">
                                {{ $referral->filleul->nom ?? $referral->filleul->telephone }}
                            </div>
                            <div style="font-size:0.75rem; color:var(--c-muted); margin-top:2px;">
                                {{ $referral->created_at->format('d/m/Y') }}
                            </div>
                        </div>
                        <div style="text-align:right; flex-shrink:0;">
                            <span class="pill pill-green" style="font-size:0.72rem;">Crédité</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    @else

        {{-- Pas encore de code --}}
        <div style="text-align:center; padding:1rem 0; color:var(--c-muted); font-size:0.85rem; line-height:1.7;">
            Votre code de parrainage sera généré automatiquement<br>
            après activation de votre premier abonnement.
        </div>

    @endif

</div>

@endif