<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    {!! SEO::generate() !!}

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">
    <link rel="sitemap" type="application/xml" href="{{ route('sitemap') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;800&family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        :root {
            --c-bg:        #0a0a0a;
            --c-bg2:       #111111;
            --c-bg3:       #1a1a1a;
            --c-green:     #00e676;
            --c-green-dim: #00c853;
            --c-green-bg:  rgba(0, 230, 118, 0.08);
            --c-gold:      #ffd600;
            --c-border:    rgba(255,255,255,0.07);
            --c-border-g:  rgba(0, 230, 118, 0.25);
            --c-text:      #f0f0f0;
            --c-muted:     #888888;
            --c-danger:    #ff3d3d;
            --c-warning:   #ffab00;
            --font-display: 'Barlow Condensed', sans-serif;
            --font-body:    'Barlow', sans-serif;
        }

        * { box-sizing: border-box; }

        html, body {
            background-color: var(--c-bg);
            color: var(--c-text);
            font-family: var(--font-body);
            font-size: 15px;
            line-height: 1.6;
            min-height: 100%;
        }

        /* Scrollbar personnalisée */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: var(--c-bg); }
        ::-webkit-scrollbar-thumb { background: var(--c-green-dim); border-radius: 2px; }

        /* Navbar */
        .navbar {
            background: rgba(10, 10, 10, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--c-border);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .navbar-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 60px;
        }

        .navbar-brand {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 1.6rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: var(--c-text);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .navbar-brand .brand-accent {
            color: var(--c-green);
        }

        .brand-dot {
            width: 8px;
            height: 8px;
            background: var(--c-green);
            border-radius: 50%;
            display: inline-block;
            animation: pulse-dot 2s ease-in-out infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.7); }
        }

        /* Logo responsive */
        .navbar-logo {
            height: 100px;
            width: auto;
            object-fit: contain;
        }

        .nav-link {
            font-family: var(--font-display);
            font-weight: 600;
            font-size: 0.95rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--c-muted);
            text-decoration: none;
            padding: 6px 0;
            position: relative;
            transition: color 0.2s;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--c-green);
            transition: width 0.25s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--c-text);
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        /* Badge abonnement dans la nav */
        .badge-abo {
            font-family: var(--font-display);
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 2px 8px;
            border-radius: 2px;
            border: 1px solid var(--c-green);
            color: var(--c-green);
            background: var(--c-green-bg);
        }

        /* Boutons */
        .btn-primary {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 0.95rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            background: var(--c-green);
            color: #000;
            border: none;
            padding: 10px 24px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.2s, transform 0.15s;
            clip-path: polygon(6px 0%, 100% 0%, calc(100% - 6px) 100%, 0% 100%);
        }

        .btn-primary:hover {
            background: var(--c-green-dim);
            transform: translateY(-1px);
        }

        .btn-outline {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 0.85rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            background: transparent;
            color: var(--c-muted);
            border: 1px solid var(--c-border);
            padding: 8px 20px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .btn-outline:hover {
            border-color: var(--c-green);
            color: var(--c-green);
        }

        /* Ticker live en haut de page */
        .ticker-bar {
            background: var(--c-green);
            color: #000;
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 0.75rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 5px 0;
            overflow: hidden;
            white-space: nowrap;
        }

        .ticker-inner {
            display: inline-block;
            animation: ticker-scroll 30s linear infinite;
        }

        @keyframes ticker-scroll {
            0%   { transform: translateX(100vw); }
            100% { transform: translateX(-100%); }
        }

        /* Contenu principal */
        .main-content {
            min-height: calc(100vh - 120px);
        }

        /* Cartes */
        .card {
            background: var(--c-bg2);
            border: 1px solid var(--c-border);
            transition: border-color 0.2s;
        }

        .card:hover {
            border-color: var(--c-border-g);
        }

        /* Badges niveaux de risque */
        .badge-faible {
            background: rgba(0, 230, 118, 0.12);
            color: var(--c-green);
            border: 1px solid rgba(0, 230, 118, 0.3);
        }

        .badge-modere {
            background: rgba(255, 171, 0, 0.12);
            color: var(--c-warning);
            border: 1px solid rgba(255, 171, 0, 0.3);
        }

        .badge-risque {
            background: rgba(255, 61, 61, 0.12);
            color: var(--c-danger);
            border: 1px solid rgba(255, 61, 61, 0.3);
        }

        /* Statuts résultats */
        .badge-gagne  { color: var(--c-green);   }
        .badge-perdu  { color: var(--c-danger);   }
        .badge-cours  { color: var(--c-warning);  }
        .badge-attente{ color: var(--c-muted);    }

        /* Séparateur doré */
        .gold-line {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--c-gold), transparent);
            opacity: 0.3;
            margin: 2rem 0;
        }

        /* Alertes flash */
        .flash-success {
            background: var(--c-green-bg);
            border-left: 3px solid var(--c-green);
            color: var(--c-green);
            padding: 12px 16px;
            font-family: var(--font-display);
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        .flash-warning {
            background: rgba(255, 171, 0, 0.08);
            border-left: 3px solid var(--c-warning);
            color: var(--c-warning);
            padding: 12px 16px;
            font-family: var(--font-display);
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        .flash-error {
            background: rgba(255, 61, 61, 0.08);
            border-left: 3px solid var(--c-danger);
            color: var(--c-danger);
            padding: 12px 16px;
            font-family: var(--font-display);
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        /* Footer */
        .footer {
            border-top: 1px solid var(--c-border);
            background: var(--c-bg);
            padding: 2rem 0 1.5rem;
        }

        .footer-brand {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 1.2rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--c-text);
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        /* Menu mobile */
        .mobile-menu {
            display: none;
            background: var(--c-bg2);
            border-top: 1px solid var(--c-border);
            padding: 1rem 1.5rem;
        }

        .mobile-menu.open {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        /* Hamburger */
        .hamburger {
            background: none;
            border: 1px solid var(--c-border);
            padding: 6px 10px;
            cursor: pointer;
            display: none;
            flex-direction: column;
            gap: 4px;
        }

        .hamburger span {
            display: block;
            width: 20px;
            height: 2px;
            background: var(--c-text);
            transition: all 0.2s;
        }

        /* ── RESPONSIVE ─────────────────────────────────── */
        @media (max-width: 768px) {
            .hamburger   { display: flex !important; }
            .nav-links   { display: none !important; }
            .badge-abo   { display: none; }

            .navbar-logo { height: 80px; }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .btn-outline { display: none; }
        }
    </style>
</head>
<body class="h-full">

    {{-- Ticker live --}}
    <div class="ticker-bar">
        <span class="ticker-inner">
            &nbsp;&nbsp;&nbsp;
            ⚽ Pronostics mis à jour quotidiennement &nbsp;•&nbsp;
            📊 Consultez notre taux de réussite sur /performances &nbsp;•&nbsp;
            🔒 Accès sécurisé réservé aux abonnés &nbsp;•&nbsp;
            💬 Paiement via Orange Money &amp; Moov Money &nbsp;•&nbsp;
            ⚽ Pronostics mis à jour quotidiennement &nbsp;•&nbsp;
            📊 Consultez notre taux de réussite sur /performances &nbsp;•&nbsp;
        </span>
    </div>

    {{-- Navbar --}}
    <nav class="navbar">
        <div class="navbar-inner">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="navbar-brand p-0">
                @if(file_exists(public_path('images/logo-bg-blanc.png')))
                    <img
                        src="{{ asset('images/logo-bg-blanc.png') }}"
                        alt="{{ config('app.name') }}"
                        class="navbar-logo"
                    >
                @else
                    <span class="brand-dot"></span>
                    {{ config('app.name') }}<span class="brand-accent">.</span>
                @endif
            </a>

            {{-- Navigation desktop --}}
            <div class="nav-links" style="display:flex; align-items:center; gap:2rem;">
                <a href="{{ route('home') }}"
                   class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    Accueil
                </a>
                <a href="{{ route('performances') }}"
                   class="nav-link {{ request()->routeIs('performances') ? 'active' : '' }}">
                    Performances
                </a>
                <a href="{{ route('calendrier.performances') }}"
                   class="nav-link {{ request()->routeIs('calendrier.performances') ? 'active' : '' }}">
                    Calendrier
                </a>
                @auth
                    <a href="{{ route('coupons.index') }}"
                       class="nav-link {{ request()->routeIs('coupons*') ? 'active' : '' }}">
                        Coupons
                    </a>
                @endauth
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                           class="nav-link {{ request()->routeIs('admin*') ? 'active' : '' }}"
                           style="color: var(--c-gold);">
                            Admin
                        </a>
                    @endif
                @endauth
            </div>

            {{-- Actions droite --}}
            <div style="display:flex; align-items:center; gap:0.75rem;">

                @auth
                    {{-- Badge abonnement --}}
                    @if(auth()->user()->abonnementActif)
                        <span class="badge-abo">
                            ● Actif — {{ auth()->user()->abonnementActif->joursRestants() }}j
                        </span>
                    @endif

                    {{-- Menu utilisateur --}}
                    <div style="position:relative;" x-data="{ open: false }">
                        <button @click="open = !open"
                                style="background:var(--c-bg3); border:1px solid var(--c-border); color:var(--c-text); padding:7px 14px; cursor:pointer; display:flex; align-items:center; gap:8px; font-family:var(--font-display); font-weight:600; font-size:0.85rem; letter-spacing:0.06em; text-transform:uppercase;">
                            {{ Str::limit(auth()->user()->nom ?? auth()->user()->telephone, 12) }}
                            <svg width="10" height="6" viewBox="0 0 10 6" fill="none">
                                <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false"
                             style="position:absolute; right:0; top:calc(100% + 6px); background:var(--c-bg2); border:1px solid var(--c-border); min-width:180px; z-index:100; display:none;"
                             x-bind:style="open ? 'display:block' : 'display:none'">

                            <a href="{{ route('dashboard') }}"
                               style="display:block; padding:10px 16px; color:var(--c-muted); text-decoration:none; font-family:var(--font-display); font-size:0.85rem; letter-spacing:0.06em; text-transform:uppercase; border-bottom:1px solid var(--c-border); transition:color 0.2s;"
                               onmouseover="this.style.color='var(--c-text)'"
                               onmouseout="this.style.color='var(--c-muted)'">
                                Mon espace
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        style="width:100%; text-align:left; padding:10px 16px; background:none; border:none; color:var(--c-danger); cursor:pointer; font-family:var(--font-display); font-size:0.85rem; letter-spacing:0.06em; text-transform:uppercase; transition:opacity 0.2s;"
                                        onmouseover="this.style.opacity='0.7'"
                                        onmouseout="this.style.opacity='1'">
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>

                @else
                    <a href="{{ route('login') }}" class="btn-outline">Connexion</a>
                    <a href="{{ route('register') }}" class="btn-primary">S'abonner</a>
                @endauth

                {{-- Hamburger mobile --}}
                <button class="hamburger" onclick="toggleMenu()" aria-label="Menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>

        {{-- Menu mobile --}}
        <div class="mobile-menu" id="mobileMenu">
            <a href="{{ route('home') }}" class="nav-link">Accueil</a>
            <a href="{{ route('performances') }}" class="nav-link">Performances</a>
            <a href="{{ route('calendrier') }}" class="nav-link">Calendrier</a>
            @auth
                <a href="{{ route('coupons.index') }}" class="nav-link">Coupons</a>
                <a href="{{ route('dashboard') }}" class="nav-link">Mon espace</a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="nav-link" style="color:var(--c-gold)">Admin</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link" style="background:none; border:none; cursor:pointer; color:var(--c-danger); padding:0;">
                        Déconnexion
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="nav-link">Connexion</a>
                <a href="{{ route('register') }}" class="btn-primary" style="width:fit-content;">S'abonner</a>
            @endauth
        </div>
    </nav>

    {{-- Messages flash --}}
    @if(session('success'))
        <div style="max-width:1200px; margin:1rem auto; padding:0 1.5rem;">
            <div class="flash-success">✓ &nbsp;{{ session('success') }}</div>
        </div>
    @endif

    @if(session('warning'))
        <div style="max-width:1200px; margin:1rem auto; padding:0 1.5rem;">
            <div class="flash-warning">⚠ &nbsp;{{ session('warning') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div style="max-width:1200px; margin:1rem auto; padding:0 1.5rem;">
            <div class="flash-error">✕ &nbsp;{{ session('error') }}</div>
        </div>
    @endif

    {{-- Contenu principal --}}
    <main class="main-content">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="footer">
    <div style="max-width:1200px; margin:0 auto; padding:0 1.5rem;">
        <div class="footer-grid">
            {{-- Colonne 1 : Brand --}}
            <div>
                <div class="footer-brand">
                    {{ config('app.name') }}<span style="color:var(--c-green)">.</span>
                </div>
                <p style="color:var(--c-muted); font-size:0.85rem; margin-top:0.75rem; line-height:1.7;">
                    Pronostics sportifs premium au Burkina Faso.
                    Paiement via Mobile Money.
                </p>
            </div>

            {{-- Colonne 2 : Navigation --}}
            <div>
                <div style="font-family:var(--font-display); font-weight:700; font-size:0.8rem; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:1rem;">
                    Navigation
                </div>
                <div style="display:flex; flex-direction:column; gap:0.5rem;">
                    <a href="{{ route('home') }}" style="color:var(--c-muted); text-decoration:none; font-size:0.9rem; transition:color 0.2s;" onmouseover="this.style.color='var(--c-green)'" onmouseout="this.style.color='var(--c-muted)'">Accueil</a>
                    <a href="{{ route('performances') }}" style="color:var(--c-muted); text-decoration:none; font-size:0.9rem; transition:color 0.2s;" onmouseover="this.style.color='var(--c-green)'" onmouseout="this.style.color='var(--c-muted)'">Performances</a>
                    <a href="{{ route('calendrier') }}" style="color:var(--c-muted); text-decoration:none; font-size:0.9rem; transition:color 0.2s;" onmouseover="this.style.color='var(--c-green)'" onmouseout="this.style.color='var(--c-muted)'">Calendrier</a>
                    <a href="{{ route('register') }}" style="color:var(--c-muted); text-decoration:none; font-size:0.9rem; transition:color 0.2s;" onmouseover="this.style.color='var(--c-green)'" onmouseout="this.style.color='var(--c-muted)'">S'abonner</a>
                </div>
            </div>

            {{-- Colonne 3 : Contact --}}
            <div>
                <div style="font-family:var(--font-display); font-weight:700; font-size:0.8rem; letter-spacing:0.1em; text-transform:uppercase; color:var(--c-muted); margin-bottom:1rem;">
                    Contact & Paiement
                </div>

                @php
                    $whatsapp  = \App\Models\Parametre::get('whatsapp_numero');
                    $orange    = \App\Models\Parametre::get('ussd_orange');
                    $moov      = \App\Models\Parametre::get('ussd_moov');
                    $wave      = \App\Models\Parametre::get('ussd_wave');
                    $telegram  = \App\Models\Parametre::get('lien_telegram');
                @endphp

                <div style="display:flex; flex-direction:column; gap:0.5rem; color:var(--c-muted); font-size:0.9rem;">

                    @if($whatsapp)
                        <span>📱 WhatsApp :
                            <a href="https://wa.me/{{ $whatsapp }}" target="_blank"
                               style="color:var(--c-green); text-decoration:none;">+{{ $whatsapp }}</a>
                        </span>
                    @endif

                    @if($telegram)
                        <span>✈️ Telegram :
                            <a href="{{ $telegram }}" target="_blank"
                               style="color:var(--c-green); text-decoration:none;">Rejoindre</a>
                        </span>
                    @endif

                    @if($orange)
                        <span>🟠 Orange Money : {{ $orange }}</span>
                    @endif

                    @if($moov)
                        <span>🔵 Moov Money : {{ $moov }}</span>
                    @endif

                    @if($wave)
                        <span>🌊 Wave : {{ $wave }}</span>
                    @endif

                    <span style="margin-top:0.5rem; font-size:0.75rem;">
                        Activation : Lun–Sam, 8h–20h
                    </span>
                </div>
            </div>
        </div>

        <div class="gold-line"></div>

        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
            <p style="color:var(--c-muted); font-size:0.78rem;">
                © {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.
            </p>
            <p style="color:var(--c-muted); font-size:0.78rem; font-style:italic;">
                ⚠ Les paris sportifs comportent des risques. Jouez de façon responsable.
            </p>
        </div>
    </div>
</footer>

    @livewireScripts

    <script>
        function toggleMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('open');
        }
    </script>

    @stack('scripts')

</body>
</html>