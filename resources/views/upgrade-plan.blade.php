@extends('layouts.app')

@section('title', 'Upgrade Plan')

@section('content')
<div style="max-width:600px; margin:0 auto; padding:2rem 1rem;">

    <div style="margin-bottom:2rem;">
        <div style="font-family:var(--font-display); font-size:0.75rem; font-weight:700; letter-spacing:0.15em; text-transform:uppercase; color:var(--c-green); margin-bottom:0.5rem;">
            ● Upgrade Plan
        </div>
        <h1 style="font-family:var(--font-display); font-size:1.8rem; font-weight:800; letter-spacing:0.03em; text-transform:uppercase; margin:0;">
            Passer à un plan supérieur
        </h1>
        <p style="font-size:0.85rem; color:var(--c-muted); margin-top:0.75rem;">
            Plan actuel : <strong style="color:var(--c-text); text-transform:capitalize;">{{ $planActif->nom }}</strong>
            — {{ $planActif->prixFormate() }}
        </p>
    </div>

    @if(session('error'))
        <div class="flash-error" style="margin-bottom:1.5rem;">✕ &nbsp;{{ session('error') }}</div>
    @endif

    @if($plansSupérieurs->isEmpty())
        <div style="background:var(--c-bg2); border:1px solid var(--c-border); padding:2rem; text-align:center;">
            <div style="font-size:2rem; margin-bottom:1rem;">🏆</div>
            <div style="font-family:var(--font-display); font-weight:700; font-size:1rem; text-transform:uppercase; color:var(--c-muted);">
                Vous êtes déjà au plan maximum.
            </div>
        </div>
    @else

        {{-- Plans disponibles --}}
        <div style="display:flex; flex-direction:column; gap:0.75rem; margin-bottom:2rem;">
            @foreach($plansSupérieurs as $plan)
                <div style="background:var(--c-bg2); border:1px solid var(--c-border-g); padding:1.25rem;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:0.75rem;">
                        <div>
                            <div style="font-family:var(--font-display); font-weight:800; font-size:1rem; text-transform:uppercase;">{{ $plan->nom }}</div>
                            <div style="font-size:0.78rem; color:var(--c-muted); margin-top:2px;">{{ $plan->duree_jours }} jours</div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-family:var(--font-display); font-weight:800; color:var(--c-green); font-size:1.1rem;">{{ $plan->prixFormate() }}</div>
                            <div style="font-size:0.75rem; color:var(--c-muted);">{{ $plan->gainJournalierFormate() }}</div>
                        </div>
                    </div>
                    <div style="font-size:0.8rem; color:var(--c-muted); display:flex; gap:1.5rem;">
                        <span>📈 Gain total : <strong style="color:var(--c-text);">{{ $plan->gainTotalFormate() }}</strong></span>
                        <span>💰 Seuil retrait : <strong style="color:var(--c-text);">{{ $plan->seuilRetraitFormate() }}</strong></span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Formulaire paiement --}}
        <div x-data="{ onglet: 'paiement' }">

            {{-- Switcher --}}
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; border:1px solid var(--c-border); overflow:hidden; margin-bottom:0;">
                <button @click="onglet = 'paiement'"
                        :style="onglet === 'paiement' ? 'background:var(--c-green); color:#000;' : 'background:var(--c-bg2); color:var(--c-muted);'"
                        style="font-family:var(--font-display); font-weight:700; font-size:0.72rem; letter-spacing:0.07em; text-transform:uppercase; padding:11px; border:none; cursor:pointer; transition:all 0.2s;">
                    💳 Formulaire
                </button>
                <button @click="onglet = 'whatsapp'"
                        :style="onglet === 'whatsapp' ? 'background:var(--c-green); color:#000;' : 'background:var(--c-bg2); color:var(--c-muted);'"
                        style="font-family:var(--font-display); font-weight:700; font-size:0.72rem; letter-spacing:0.07em; text-transform:uppercase; padding:11px; border:none; border-left:1px solid var(--c-border); cursor:pointer; transition:all 0.2s;">
                    💬 WhatsApp
                </button>
                <button @click="onglet = 'code'"
                        :style="onglet === 'code' ? 'background:var(--c-green); color:#000;' : 'background:var(--c-bg2); color:var(--c-muted);'"
                        style="font-family:var(--font-display); font-weight:700; font-size:0.72rem; letter-spacing:0.07em; text-transform:uppercase; padding:11px; border:none; border-left:1px solid var(--c-border); cursor:pointer; transition:all 0.2s;">
                    🔑 Code
                </button>
            </div>

            {{-- Onglet Formulaire --}}
            <div x-show="onglet === 'paiement'"
                 style="background:var(--c-bg2); border:1px solid var(--c-border); border-top:none; padding:1.5rem;">

                @if($dernierPaiement && $dernierPaiement->statut === 'en_attente')
                    <div style="background:rgba(255,171,0,0.06); border:1px solid rgba(255,171,0,0.3); padding:1rem; text-align:center;">
                        <div style="font-family:var(--font-display); font-weight:700; color:var(--c-warning); font-size:0.85rem; letter-spacing:0.06em; text-transform:uppercase; margin-bottom:0.5rem;">
                            ⏳ Paiement en attente
                        </div>
                        <div style="font-size:0.82rem; color:var(--c-muted);">
                            Votre paiement est en cours de validation.
                        </div>
                    </div>
                @else
                    <form method="POST" action="{{ route('dashboard.paiement') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Choix du plan --}}
                        <div style="margin-bottom:1rem;">
                            <label style="display:block; font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.5rem;">
                                Plan *
                            </label>
                            <div style="display:flex; flex-direction:column; gap:0.5rem;">
                                @foreach($plansSupérieurs as $plan)
                                    <label class="plan-label"
                                           style="display:flex; align-items:center; justify-content:space-between; background:var(--c-bg3); border:1px solid {{ old('plan') === $plan->slug ? 'var(--c-green)' : 'var(--c-border)' }}; padding:0.75rem 1rem; cursor:pointer; transition:border-color 0.2s;"
                                           onclick="this.style.borderColor='var(--c-green)'; document.querySelectorAll('.plan-label').forEach(el => { if(el !== this) el.style.borderColor='var(--c-border)' })">
                                        <div style="display:flex; align-items:center; gap:0.75rem;">
                                            <input type="radio" name="plan" value="{{ $plan->slug }}"
                                                   {{ old('plan') === $plan->slug ? 'checked' : '' }}
                                                   style="accent-color:var(--c-green);">
                                            <div>
                                                <div style="font-family:var(--font-display); font-weight:700; font-size:0.9rem;">{{ $plan->nom }}</div>
                                                <div style="font-size:0.75rem; color:var(--c-muted);">{{ $plan->duree_jours }} jours</div>
                                            </div>
                                        </div>
                                        <div style="font-family:var(--font-display); font-weight:800; color:var(--c-green); font-size:1rem; white-space:nowrap; margin-left:0.5rem;">
                                            {{ $plan->prixFormate() }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('plan') <div style="color:var(--c-danger); font-size:0.8rem; margin-top:0.4rem; font-family:var(--font-display); font-weight:600;">✕ {{ $message }}</div> @enderror
                        </div>

                        {{-- Opérateur --}}
                        <div style="margin-bottom:1rem;">
                            <label style="display:block; font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.5rem;">
                                Opérateur *
                            </label>
                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.5rem;">
                                <label style="display:flex; align-items:center; gap:0.5rem; background:var(--c-bg3); border:1px solid var(--c-border); padding:0.75rem; cursor:pointer;">
                                    <input type="radio" name="operateur" value="orange" {{ old('operateur') === 'orange' ? 'checked' : '' }} style="accent-color:#ff6600;">
                                    <span>🟠 Orange Money</span>
                                </label>
                                <label style="display:flex; align-items:center; gap:0.5rem; background:var(--c-bg3); border:1px solid var(--c-border); padding:0.75rem; cursor:pointer;">
                                    <input type="radio" name="operateur" value="moov" {{ old('operateur') === 'moov' ? 'checked' : '' }} style="accent-color:#0066cc;">
                                    <span>🔵 Moov Money</span>
                                </label>
                            </div>
                            @error('operateur') <div style="color:var(--c-danger); font-size:0.8rem; margin-top:0.4rem; font-family:var(--font-display); font-weight:600;">✕ {{ $message }}</div> @enderror
                        </div>

                        {{-- Numéros --}}
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.5rem; margin-bottom:1.25rem;">
                            <div style="background:var(--c-bg3); border:1px solid var(--c-border); padding:0.75rem; text-align:center;">
                                <div style="font-size:0.7rem; color:var(--c-muted); margin-bottom:0.3rem;">🟠 Orange Money</div>
                                <div style="font-family:var(--font-display); font-weight:800; color:var(--c-gold); font-size:1.1rem; letter-spacing:0.08em;">{{ $ussdOrange }}</div>
                            </div>
                            <div style="background:var(--c-bg3); border:1px solid var(--c-border); padding:0.75rem; text-align:center;">
                                <div style="font-size:0.7rem; color:var(--c-muted); margin-bottom:0.3rem;">🔵 Moov Money</div>
                                <div style="font-family:var(--font-display); font-weight:800; color:var(--c-gold); font-size:1.1rem; letter-spacing:0.08em;">{{ $ussdMoov }}</div>
                            </div>
                        </div>

                        {{-- Capture --}}
                        <div style="margin-bottom:1.25rem;">
                            <label style="display:block; font-family:var(--font-display); font-size:0.72rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:0.5rem;">
                                Capture d'écran *
                            </label>
                            <label for="capture"
                                   style="display:flex; flex-direction:column; align-items:center; justify-content:center; gap:0.5rem; border:1px dashed var(--c-border-g); background:var(--c-bg3); padding:1.5rem; cursor:pointer; transition:border-color 0.2s;"
                                   onmouseover="this.style.borderColor='var(--c-green)'"
                                   onmouseout="this.style.borderColor='var(--c-border-g)'">
                                <span style="font-size:1.5rem;">📷</span>
                                <span style="font-family:var(--font-display); font-size:0.8rem; font-weight:600; letter-spacing:0.06em; text-transform:uppercase; color:var(--c-muted);">Cliquez pour choisir une image</span>
                                <span style="font-size:0.72rem; color:var(--c-muted);">JPG, PNG — max 3 Mo</span>
                                <input type="file" id="capture" name="capture" accept="image/jpg,image/jpeg,image/png"
                                       style="display:none;"
                                       onchange="document.getElementById('capture-name').textContent = this.files[0]?.name ?? ''">
                            </label>
                            <div id="capture-name" style="font-size:0.78rem; color:var(--c-green); margin-top:0.4rem; text-align:center; font-family:var(--font-display);"></div>
                            @error('capture') <div style="color:var(--c-danger); font-size:0.8rem; margin-top:0.4rem; font-family:var(--font-display); font-weight:600;">✕ {{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="btn-primary" style="width:100%; justify-content:center; padding:12px;">
                            Envoyer ma demande →
                        </button>

                    </form>
                @endif

                <div style="margin-top:1rem; font-size:0.78rem; color:var(--c-muted); text-align:center;">
                    Délai de validation : sous 24h — Lun–Sam, 8h–20h
                </div>
            </div>

            {{-- Onglet WhatsApp --}}
            <div x-show="onglet === 'whatsapp'"
                 style="background:var(--c-bg2); border:1px solid var(--c-border-g); border-top:none; padding:1.5rem;">

                <p style="font-size:0.84rem; color:var(--c-muted); margin-bottom:1.25rem; line-height:1.6;">
                    Payez via Mobile Money et envoyez votre capture directement sur WhatsApp.
                </p>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.5rem; margin-bottom:1.25rem;">
                    <div style="background:var(--c-bg3); border:1px solid var(--c-border); padding:0.75rem; text-align:center;">
                        <div style="font-size:0.7rem; color:var(--c-muted); margin-bottom:0.3rem;">🟠 Orange Money</div>
                        <div style="font-family:var(--font-display); font-weight:800; color:var(--c-gold); font-size:1.1rem; letter-spacing:0.08em;">{{ $ussdOrange }}</div>
                    </div>
                    <div style="background:var(--c-bg3); border:1px solid var(--c-border); padding:0.75rem; text-align:center;">
                        <div style="font-size:0.7rem; color:var(--c-muted); margin-bottom:0.3rem;">🔵 Moov Money</div>
                        <div style="font-family:var(--font-display); font-weight:800; color:var(--c-gold); font-size:1.1rem; letter-spacing:0.08em;">{{ $ussdMoov }}</div>
                    </div>
                </div>

                <a href="{{ $whatsappUrl }}" target="_blank"
                   style="display:flex; align-items:center; justify-content:center; gap:0.75rem; width:100%; background:#25D366; color:#fff; padding:13px; font-family:var(--font-display); font-weight:700; font-size:0.9rem; letter-spacing:0.08em; text-transform:uppercase; text-decoration:none; transition:background 0.2s;"
                   onmouseover="this.style.background='#1ebe5d'"
                   onmouseout="this.style.background='#25D366'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="#fff">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    Envoyer ma capture sur WhatsApp
                </a>

                <div style="margin-top:1rem; text-align:center; font-size:0.78rem; color:var(--c-muted);">
                    Vous recevrez votre code après vérification — sous 24h, Lun–Sam 8h–20h
                </div>
            </div>

            {{-- Onglet Code --}}
            <div x-show="onglet === 'code'"
                style="background:var(--c-bg2); border:1px solid var(--c-border-g); border-top:none; padding:1.5rem;">

                <p style="font-size:0.84rem; color:var(--c-muted); margin-bottom:1.25rem; line-height:1.6;">
                    Saisissez le code reçu via WhatsApp après validation de votre paiement d'upgrade.
                </p>

                <form method="POST" action="{{ route('dashboard.activer') }}">
                    @csrf
                    <div style="margin-bottom:1rem;">
                        <input type="text" name="code" value="{{ old('code') }}"
                            placeholder="ACC-XXXXXXXX" maxlength="20"
                            style="width:100%; background:var(--c-bg3); border:1px solid {{ $errors->has('code') ? 'var(--c-danger)' : 'var(--c-border-g)' }}; color:var(--c-text); padding:12px 14px; font-family:var(--font-display); font-size:1.1rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; outline:none; text-align:center;"
                            oninput="this.value = this.value.toUpperCase()">
                        @error('code')
                            <div style="color:var(--c-danger); font-size:0.8rem; margin-top:0.4rem; font-family:var(--font-display); font-weight:600; text-align:center;">
                                ✕ {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <button type="submit" class="btn-primary" style="width:100%; justify-content:center; padding:12px;">
                        Activer l'upgrade →
                    </button>
                </form>

                <div style="margin-top:1rem; text-align:center; font-size:0.78rem; color:var(--c-muted);">
                    Horaires d'activation : Lun–Sam, 8h–20h
                </div>

            </div>

        </div>

        <div style="margin-top:1.5rem; text-align:center;">
            <a href="{{ route('dashboard') }}" style="font-size:0.82rem; color:var(--c-muted); text-decoration:none; font-family:var(--font-display); font-weight:600; letter-spacing:0.06em; text-transform:uppercase;">
                ← Retour au dashboard
            </a>
        </div>

    @endif

</div>
@endsection