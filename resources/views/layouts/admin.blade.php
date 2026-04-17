<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Admin — @yield('title', 'Back-office') | {{ config('app.name') }}</title>

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
            --c-sidebar:   #0d0d0d;
            --c-green:     #00e676;
            --c-green-dim: #00c853;
            --c-green-bg:  rgba(0, 230, 118, 0.08);
            --c-gold:      #ffd600;
            --c-border:    rgba(255,255,255,0.07);
            --c-border-g:  rgba(0, 230, 118, 0.2);
            --c-text:      #f0f0f0;
            --c-muted:     #666666;
            --c-danger:    #ff3d3d;
            --c-warning:   #ffab00;
            --font-display: 'Barlow Condensed', sans-serif;
            --font-body:    'Barlow', sans-serif;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            background: var(--c-bg);
            color: var(--c-text);
            font-family: var(--font-body);
            font-size: 14px;
            height: 100%;
        }

        ::-webkit-scrollbar { width: 3px; }
        ::-webkit-scrollbar-track { background: var(--c-bg); }
        ::-webkit-scrollbar-thumb { background: var(--c-border-g); }

        /* Layout admin : sidebar + contenu */
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 220px;
            min-width: 220px;
            background: var(--c-sidebar);
            border-right: 1px solid var(--c-border);
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-logo {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid var(--c-border);
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 1.3rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--c-text);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sidebar-logo span { color: var(--c-gold); }

        .sidebar-logo img {
            height: 60px;
            width: auto;
            object-fit: contain;
        }

        .sidebar-section {
            padding: 1.25rem 1rem 0.5rem;
            font-family: var(--font-display);
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--c-muted);
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 1rem;
            color: var(--c-muted);
            text-decoration: none;
            font-family: var(--font-display);
            font-weight: 600;
            font-size: 0.9rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            border-left: 2px solid transparent;
            transition: all 0.2s;
        }

        .sidebar-link:hover {
            color: var(--c-text);
            background: rgba(255,255,255,0.03);
            border-left-color: var(--c-border);
        }

        .sidebar-link.active {
            color: var(--c-green);
            background: var(--c-green-bg);
            border-left-color: var(--c-green);
        }

        .sidebar-link .icon {
            width: 16px;
            text-align: center;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .sidebar-count {
            margin-left: auto;
            background: var(--c-danger);
            color: #fff;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 10px;
            font-family: var(--font-body);
        }

        .sidebar-count.green {
            background: var(--c-green);
            color: #000;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 1rem;
            border-top: 1px solid var(--c-border);
        }

        .sidebar-user {
            font-family: var(--font-display);
            font-size: 0.8rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--c-muted);
            margin-bottom: 0.75rem;
        }

        .sidebar-user strong {
            display: block;
            color: var(--c-gold);
            font-size: 0.9rem;
        }

        /* Zone principale */
        .admin-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            min-width: 0;
        }

        /* Topbar desktop */
        .admin-topbar {
            background: var(--c-bg2);
            border-bottom: 1px solid var(--c-border);
            padding: 0 1.5rem;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .topbar-title {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 1.15rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--c-text);
        }

        .topbar-breadcrumb {
            font-family: var(--font-display);
            font-size: 0.75rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--c-muted);
            margin-top: 1px;
        }

        /* Contenu admin */
        .admin-content {
            padding: 1.75rem;
            flex: 1;
        }

        /* Cartes stats */
        .stat-card {
            background: var(--c-bg2);
            border: 1px solid var(--c-border);
            padding: 1.25rem;
            transition: border-color 0.2s;
        }

        .stat-card:hover { border-color: var(--c-border-g); }

        .stat-label {
            font-family: var(--font-display);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--c-muted);
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-family: var(--font-display);
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
            color: var(--c-text);
        }

        .stat-value.green { color: var(--c-green); }
        .stat-value.gold  { color: var(--c-gold); }
        .stat-value.red   { color: var(--c-danger); }

        /* Tables admin */
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        .admin-table th {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 0.72rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--c-muted);
            padding: 10px 14px;
            text-align: left;
            border-bottom: 1px solid var(--c-border);
            background: var(--c-bg3);
            white-space: nowrap;
        }

        .admin-table td {
            padding: 12px 14px;
            border-bottom: 1px solid var(--c-border);
            color: var(--c-text);
            vertical-align: middle;
        }

        .admin-table tr:hover td {
            background: rgba(255,255,255,0.02);
        }

        /* Boutons admin */
        .btn-sm-green {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 0.75rem;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            background: var(--c-green);
            color: #000;
            border: none;
            padding: 5px 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: background 0.2s;
        }

        .btn-sm-green:hover { background: var(--c-green-dim); }

        .btn-sm-red {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 0.75rem;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            background: transparent;
            color: var(--c-danger);
            border: 1px solid rgba(255, 61, 61, 0.3);
            padding: 5px 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.2s;
        }

        .btn-sm-red:hover {
            background: rgba(255, 61, 61, 0.1);
            border-color: var(--c-danger);
        }

        .btn-sm-outline {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 0.75rem;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            background: transparent;
            color: var(--c-muted);
            border: 1px solid var(--c-border);
            padding: 5px 14px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-sm-outline:hover {
            color: var(--c-text);
            border-color: rgba(255,255,255,0.2);
        }

        /* Badges statuts */
        .pill {
            display: inline-block;
            font-family: var(--font-display);
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 2px 8px;
            border-radius: 2px;
        }

        .pill-green  { background: rgba(0,230,118,0.12);  color: var(--c-green);   border: 1px solid rgba(0,230,118,0.3); }
        .pill-red    { background: rgba(255,61,61,0.12);   color: var(--c-danger);  border: 1px solid rgba(255,61,61,0.3); }
        .pill-yellow { background: rgba(255,171,0,0.12);   color: var(--c-warning); border: 1px solid rgba(255,171,0,0.3); }
        .pill-gray   { background: rgba(255,255,255,0.05); color: var(--c-muted);   border: 1px solid var(--c-border); }
        .pill-gold   { background: rgba(255,214,0,0.12);   color: var(--c-gold);    border: 1px solid rgba(255,214,0,0.3); }

        /* Alertes flash admin */
        .flash-success { background: rgba(0,230,118,0.08); border-left: 3px solid var(--c-green); color: var(--c-green); padding: 10px 14px; font-family: var(--font-display); font-weight: 600; letter-spacing: 0.04em; text-transform: uppercase; font-size: 0.82rem; margin-bottom: 1rem; }
        .flash-error   { background: rgba(255,61,61,0.08); border-left: 3px solid var(--c-danger); color: var(--c-danger); padding: 10px 14px; font-family: var(--font-display); font-weight: 600; letter-spacing: 0.04em; text-transform: uppercase; font-size: 0.82rem; margin-bottom: 1rem; }

        /* ── Topbar mobile ────────────────────────────── */
        .admin-topbar-mobile {
            display: none;
            background: var(--c-bg2);
            border-bottom: 1px solid var(--c-border);
            padding: 0 1rem;
            height: 52px;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        /* Overlay + sidebar mobile */
        .mobile-nav-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            z-index: 98;
        }

        .mobile-sidebar {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100vh;
            background: var(--c-sidebar);
            border-right: 1px solid var(--c-border);
            z-index: 99;
            overflow-y: auto;
            flex-direction: column;
        }

        /* ── Responsive ───────────────────────────────── */
        @media (max-width: 768px) {
            .sidebar              { display: none; }
            .admin-topbar         { display: none; }
            .admin-topbar-mobile  { display: flex; }
            .admin-content        { padding: 1rem; }
            .mobile-sidebar.open  { display: flex; }
            .mobile-nav-overlay.open { display: block; }
        }
    </style>
</head>

<body>

{{-- ── TOPBAR MOBILE ───────────────────────────────────── --}}
<div class="admin-topbar-mobile">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-logo" style="padding:0; border:none; font-size:1.1rem;">
        <img
            src="{{ asset('images/logo-bg-blanc.png') }}"
            alt="{{ config('app.name') }}"
            style="height:80px;"
            onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
        <span style="display:none;">⚙ {{ config('app.name') }}<span style="color:var(--c-gold);">.</span></span>
    </a>
    <div style="display:flex; align-items:center; gap:0.75rem;">
        <span style="font-family:var(--font-display); font-size:0.85rem; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; color:var(--c-text);">
            @yield('title', 'Dashboard')
        </span>
        <button onclick="toggleMobileSidebar()"
                style="background:none; border:1px solid var(--c-border); padding:6px 10px; cursor:pointer; display:flex; flex-direction:column; gap:4px;">
            <span style="display:block; width:18px; height:2px; background:var(--c-text);"></span>
            <span style="display:block; width:18px; height:2px; background:var(--c-text);"></span>
            <span style="display:block; width:18px; height:2px; background:var(--c-text);"></span>
        </button>
    </div>
</div>

{{-- ── OVERLAY MOBILE ──────────────────────────────────── --}}
<div class="mobile-nav-overlay" id="mobileOverlay" onclick="toggleMobileSidebar()"></div>

{{-- ── SIDEBAR MOBILE ──────────────────────────────────── --}}
<aside class="mobile-sidebar" id="mobileSidebar">

    <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
        @if(file_exists(public_path('images/logo-bg-blanc.png')))
            <img src="{{ asset('images/logo-bg-blanc.png') }}" alt="{{ config('app.name') }}">
        @else
            ⚙ {{ config('app.name') }}<span>.</span>
        @endif
    </a>

    <nav style="flex:1; padding:0.5rem 0;">
        <div class="sidebar-section">Principal</div>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="icon">▦</span> Dashboard
        </a>

        <div class="sidebar-section">Paiements</div>
        <a href="{{ route('admin.paiements.index') }}" class="sidebar-link {{ request()->routeIs('admin.paiements*') ? 'active' : '' }}">
            <span class="icon">💳</span> Paiements
            @if(\App\Models\Paiement::enAttente()->count() > 0)
                <span class="sidebar-count">{{ \App\Models\Paiement::enAttente()->count() }}</span>
            @endif
        </a>

        <a href="{{ route('admin.retraits.index') }}" class="sidebar-link {{ request()->routeIs('admin.retraits*') ? 'active' : '' }}">
            <span class="icon">💸</span> Retraits
            @if(\App\Models\DemandeRetrait::where('statut', 'en_attente')->count() > 0)
                <span class="sidebar-count">{{ \App\Models\DemandeRetrait::where('statut', 'en_attente')->count() }}</span>
            @endif
        </a>

        <a href="{{ route('admin.codes.index') }}" class="sidebar-link {{ request()->routeIs('admin.codes*') ? 'active' : '' }}">
            <span class="icon">🔑</span> Codes d'accès
        </a>

        <div class="sidebar-section">Contenu</div>
        <a href="{{ route('admin.coupons.index') }}" class="sidebar-link {{ request()->routeIs('admin.coupons*') ? 'active' : '' }}">
            <span class="icon">🎟</span> Coupons
        </a>

        <div class="sidebar-section">Membres</div>
        <a href="{{ route('admin.utilisateurs.index') }}" class="sidebar-link {{ request()->routeIs('admin.utilisateurs*') ? 'active' : '' }}">
            <span class="icon">👥</span> Utilisateurs
        </a>
        <a href="{{ route('admin.plans.index') }}" class="sidebar-link {{ request()->routeIs('admin.plans*') ? 'active' : '' }}">
            <span class="icon">💰</span> Plans & Tarifs
        </a>

        <div class="sidebar-section">Système</div>
        <a href="{{ route('admin.parametres.index') }}" class="sidebar-link {{ request()->routeIs('admin.parametres*') ? 'active' : '' }}">
            <span class="icon">⚙</span> Paramètres
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            Connecté en tant que
            <strong>{{ auth()->user()->nom ?? auth()->user()->telephone }}</strong>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    style="width:100%; background:none; border:1px solid var(--c-border); color:var(--c-muted); padding:7px; cursor:pointer; font-family:var(--font-display); font-size:0.75rem; letter-spacing:0.08em; text-transform:uppercase; transition:all 0.2s;"
                    onmouseover="this.style.borderColor='var(--c-danger)'; this.style.color='var(--c-danger)'"
                    onmouseout="this.style.borderColor='var(--c-border)'; this.style.color='var(--c-muted)'">
                Déconnexion
            </button>
        </form>
    </div>

</aside>

{{-- ── LAYOUT PRINCIPAL ────────────────────────────────── --}}
<div class="admin-layout">

    {{-- Sidebar desktop --}}
    <aside class="sidebar">

        <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
            @if(file_exists(public_path('images/logo-bg-blanc.png')))
                <img src="{{ asset('images/logo-bg-blanc.png') }}" alt="{{ config('app.name') }}">
            @else
                ⚙ {{ config('app.name') }}<span>.</span>
            @endif
        </a>

        <nav style="flex:1; padding:0.5rem 0;">
            <div class="sidebar-section">Principal</div>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="icon">▦</span> Dashboard
            </a>

            <div class="sidebar-section">Paiements</div>
            <a href="{{ route('admin.paiements.index') }}" class="sidebar-link {{ request()->routeIs('admin.paiements*') ? 'active' : '' }}">
                <span class="icon">💳</span> Paiements
                @php $enAttente = \App\Models\Paiement::enAttente()->count(); @endphp
                @if($enAttente > 0)
                    <span class="sidebar-count">{{ $enAttente }}</span>
                @endif
            </a>
            <a href="{{ route('admin.codes.index') }}" class="sidebar-link {{ request()->routeIs('admin.codes*') ? 'active' : '' }}">
                <span class="icon">🔑</span> Codes d'accès
            </a>

            <a href="{{ route('admin.retraits.index') }}" class="sidebar-link {{ request()->routeIs('admin.retraits*') ? 'active' : '' }}">
                <span class="icon">💸</span> Retraits
                @php $retraitsEnAttente = \App\Models\DemandeRetrait::where('statut', 'en_attente')->count(); @endphp
                @if($retraitsEnAttente > 0)
                    <span class="sidebar-count">{{ $retraitsEnAttente }}</span>
                @endif
            </a>

            <div class="sidebar-section">Contenu</div>
            <a href="{{ route('admin.coupons.index') }}" class="sidebar-link {{ request()->routeIs('admin.coupons*') ? 'active' : '' }}">
                <span class="icon">🎟</span> Coupons
            </a>

            <div class="sidebar-section">Membres</div>
            <a href="{{ route('admin.utilisateurs.index') }}" class="sidebar-link {{ request()->routeIs('admin.utilisateurs*') ? 'active' : '' }}">
                <span class="icon">👥</span> Utilisateurs
            </a>
            <a href="{{ route('admin.plans.index') }}" class="sidebar-link {{ request()->routeIs('admin.plans*') ? 'active' : '' }}">
                <span class="icon">💰</span> Plans & Tarifs
            </a>

            <div class="sidebar-section">Système</div>
            <a href="{{ route('admin.parametres.index') }}" class="sidebar-link {{ request()->routeIs('admin.parametres*') ? 'active' : '' }}">
                <span class="icon">⚙</span> Paramètres
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                Connecté en tant que
                <strong>{{ auth()->user()->nom ?? auth()->user()->telephone }}</strong>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        style="width:100%; background:none; border:1px solid var(--c-border); color:var(--c-muted); padding:7px; cursor:pointer; font-family:var(--font-display); font-size:0.75rem; letter-spacing:0.08em; text-transform:uppercase; transition:all 0.2s;"
                        onmouseover="this.style.borderColor='var(--c-danger)'; this.style.color='var(--c-danger)'"
                        onmouseout="this.style.borderColor='var(--c-border)'; this.style.color='var(--c-muted)'">
                    Déconnexion
                </button>
            </form>
        </div>

    </aside>

    {{-- Zone principale --}}
    <div class="admin-main">

        {{-- Topbar desktop --}}
        <header class="admin-topbar">
            <div>
                <div class="topbar-title">@yield('title', 'Dashboard')</div>
                <div class="topbar-breadcrumb">Admin &rsaquo; @yield('title', 'Dashboard')</div>
            </div>
            <div style="font-family:var(--font-display); font-size:0.8rem; color:var(--c-muted); letter-spacing:0.06em; text-transform:uppercase;">
                {{ now()->locale('fr')->isoFormat('dddd D MMM YYYY') }}
            </div>
        </header>

        {{-- Contenu --}}
        <main class="admin-content">
            @if(session('success'))
                <div class="flash-success">✓ &nbsp;{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="flash-error">✕ &nbsp;{{ session('error') }}</div>
            @endif
            @yield('content')
        </main>

    </div>

</div>

@livewireScripts
@stack('scripts')

<script>
function toggleMobileSidebar() {
    document.getElementById('mobileSidebar').classList.toggle('open');
    document.getElementById('mobileOverlay').classList.toggle('open');
}
</script>

</body>
</html>