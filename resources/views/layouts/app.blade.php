<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'EMS') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
        <script src="https://unpkg.com/lucide@latest"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
 
        <style>
            :root {
                --bg:        #0d1117;
                --bg-2:      #161b22;
                --bg-3:      #1c2333;
                --border:    rgba(255,255,255,0.08);
                --border-2:  rgba(255,255,255,0.13);
                --green:     #16a34a;
                --green-lt:  #4ade80;
                --green-dim: rgba(22,163,74,0.15);
                --text:      #e6edf3;
                --text-2:    rgba(230,237,243,0.6);
                --text-3:    rgba(230,237,243,0.35);
                --font-head: 'Syne', sans-serif;
                --font-body: 'DM Sans', sans-serif;
            }
 
            *, *::before, *::after { box-sizing: border-box; }
 
            body {
                font-family: var(--font-body);
                background: var(--bg);
                color: var(--text);
                min-height: 100vh;
                margin: 0;
            }

             /* Subtle grid pattern on body */
            body::before {
                content: '';
                position: fixed;
                inset: 0;
                z-index: 0;
                background-image:
                    linear-gradient(rgba(255,255,255,0.015) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255,255,255,0.015) 1px, transparent 1px);
                background-size: 40px 40px;
                pointer-events: none;
            }
 
            #app-shell {
                position: relative;
                z-index: 1;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }
 
            main { flex: 1; }

            /* ── Scrollbar ── */
            ::-webkit-scrollbar { width: 6px; }
            ::-webkit-scrollbar-track { background: var(--bg); }
            ::-webkit-scrollbar-thumb { background: var(--bg-3); border-radius: 99px; }

            /* ── Cards ── */
            .card {
                background: var(--bg-2);
                border: 1px solid var(--border);
                border-radius: 14px;
                overflow: hidden;
            }
            .card-accent { border-top: 2px solid var(--green); }

            /* ── Stat card ── */
            .stat-card {
                background: var(--bg-2);
                border: 1px solid var(--border);
                border-radius: 14px;
                padding: 1.25rem 1.5rem;
            }
            .stat-card .stat-value {
                font-family: var(--font-head);
                font-size: 2rem;
                font-weight: 800;
                color: var(--text);
                line-height: 1;
            }
            .stat-card .stat-label {
                font-size: 0.8rem;
                color: var(--text-3);
                margin-top: 0.35rem;
                text-transform: uppercase;
                letter-spacing: 0.06em;
            }

            /* ── Buttons ── */
            .btn {
                display: inline-flex;
                align-items: center;
                gap: 0.4rem;
                padding: 0.55rem 1.1rem;
                border-radius: 8px;
                font-family: var(--font-body);
                font-weight: 500;
                font-size: 0.875rem;
                text-decoration: none;
                border: none;
                cursor: pointer;
                transition: transform 0.15s, box-shadow 0.15s, background 0.15s, opacity 0.15s;
            }
            .btn:hover { transform: translateY(-1px); }
            .btn:active { transform: translateY(0); }
 
            .btn-primary {
                background: var(--green);
                color: #fff;
                box-shadow: 0 3px 14px rgba(22,163,74,0.4);
            }
            .btn-primary:hover {
                background: #15803d;
                box-shadow: 0 6px 20px rgba(22,163,74,0.55);
            }
 
            .btn-ghost {
                background: var(--bg-3);
                color: var(--text-2);
                border: 1px solid var(--border);
            }
            .btn-ghost:hover { background: rgba(255,255,255,0.08); color: var(--text); }
 
            .btn-danger {
                background: rgba(239,68,68,0.12);
                color: #f87171;
                border: 1px solid rgba(239,68,68,0.2);
            }
            .btn-danger:hover { background: rgba(239,68,68,0.2); }
 
            .btn-sm { padding: 0.4rem 0.85rem; font-size: 0.8rem; }
 
            /* ── Inputs ── */
            .form-input, .form-select, .form-textarea {
                width: 100%;
                background: var(--bg-3);
                border: 1px solid var(--border-2);
                border-radius: 8px;
                padding: 0.65rem 0.9rem;
                font-family: var(--font-body);
                font-size: 0.9rem;
                color: var(--text);
                outline: none;
                transition: border-color 0.2s, box-shadow 0.2s;
            }
            .form-input::placeholder, .form-textarea::placeholder { color: var(--text-3); }
            .form-input:focus, .form-select:focus, .form-textarea:focus {
                border-color: var(--green);
                box-shadow: 0 0 0 3px rgba(22,163,74,0.18);
            }
            .form-select { cursor: pointer; }
            .form-select option { background: var(--bg-2); }
            .form-label {
                display: block;
                font-size: 0.75rem;
                font-weight: 500;
                color: var(--text-3);
                text-transform: uppercase;
                letter-spacing: 0.07em;
                margin-bottom: 0.4rem;
            }
            .form-error { font-size: 0.78rem; color: #f87171; margin-top: 0.3rem; }
 
            /* ── Badges ── */
            .badge {
                display: inline-flex;
                align-items: center;
                padding: 0.2rem 0.65rem;
                border-radius: 999px;
                font-size: 0.72rem;
                font-weight: 600;
                letter-spacing: 0.04em;
            }
            .badge-green  { background: rgba(74,222,128,0.12); color: #4ade80; border: 1px solid rgba(74,222,128,0.2); }
            .badge-amber  { background: rgba(251,191,36,0.12); color: #fbbf24; border: 1px solid rgba(251,191,36,0.2); }
            .badge-red    { background: rgba(248,113,113,0.12); color: #f87171; border: 1px solid rgba(248,113,113,0.2); }
            .badge-blue   { background: rgba(96,165,250,0.12); color: #60a5fa; border: 1px solid rgba(96,165,250,0.2); }
            .badge-purple { background: rgba(167,139,250,0.12); color: #a78bfa; border: 1px solid rgba(167,139,250,0.2); }
            .badge-gray   { background: rgba(255,255,255,0.07); color: var(--text-2); border: 1px solid var(--border); }
 
            /* ── Alerts ── */
            .alert { padding: 0.75rem 1rem; border-radius: 10px; font-size: 0.875rem; }
            .alert-success { background: rgba(74,222,128,0.08); border: 1px solid rgba(74,222,128,0.2); color: #4ade80; }
            .alert-error   { background: rgba(248,113,113,0.08); border: 1px solid rgba(248,113,113,0.2); color: #f87171; }
            .alert-amber   { background: rgba(251,191,36,0.08); border: 1px solid rgba(251,191,36,0.2); color: #fbbf24; }
 
            /* ── Page header ── */
            .page-header { margin-bottom: 1.5rem; }
            .page-header h1 {
                font-family: var(--font-head);
                font-size: 1.6rem;
                font-weight: 800;
                color: var(--text);
                letter-spacing: -0.03em;
            }
            .page-header p { font-size: 0.875rem; color: var(--text-3); margin-top: 0.25rem; }
 
            /* ── Back link ── */
            .back-link {
                display: inline-flex;
                align-items: center;
                gap: 0.4rem;
                font-size: 0.83rem;
                color: var(--text-3);
                text-decoration: none;
                margin-bottom: 1.25rem;
                transition: color 0.2s;
            }
            .back-link:hover { color: var(--green-lt); }
 
            /* ── Table ── */
            .ems-table { width: 100%; border-collapse: collapse; }
            .ems-table th {
                padding: 0.75rem 1.25rem;
                text-align: left;
                font-size: 0.7rem;
                font-weight: 600;
                color: var(--text-3);
                text-transform: uppercase;
                letter-spacing: 0.08em;
                border-bottom: 1px solid var(--border);
            }
            .ems-table td {
                padding: 1rem 1.25rem;
                font-size: 0.875rem;
                color: var(--text-2);
                border-bottom: 1px solid var(--border);
            }
            .ems-table tr:last-child td { border-bottom: none; }
            .ems-table tr:hover td { background: rgba(255,255,255,0.02); }
 
            /* ── Detail list ── */
            .detail-row {
                display: grid;
                grid-template-columns: 140px 1fr;
                gap: 1rem;
                padding: 0.85rem 1.5rem;
                border-bottom: 1px solid var(--border);
                font-size: 0.875rem;
            }
            .detail-row:last-child { border-bottom: none; }
            .detail-key { color: var(--text-3); font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.06em; padding-top: 0.1rem; }
            .detail-val { color: var(--text); }
 
            /* ── Info tiles ── */
            .info-tile {
                display: flex;
                align-items: center;
                gap: 0.85rem;
                padding: 0.85rem 1rem;
                background: var(--bg-3);
                border: 1px solid var(--border);
                border-radius: 10px;
            }
            .info-tile-icon {
                width: 36px; height: 36px;
                border-radius: 8px;
                display: flex; align-items: center; justify-content: center;
                flex-shrink: 0;
            }
            .info-tile-label { font-size: 0.7rem; color: var(--text-3); text-transform: uppercase; letter-spacing: 0.06em; }
            .info-tile-value { font-size: 0.875rem; font-weight: 500; color: var(--text); margin-top: 0.1rem; }
 
            /* ── Avatar ── */
            .avatar {
                width: 36px; height: 36px;
                border-radius: 10px;
                display: flex; align-items: center; justify-content: center;
                font-weight: 700; font-size: 0.875rem;
                background: var(--green-dim);
                color: var(--green-lt);
                flex-shrink: 0;
            }
            .avatar-lg { width: 56px; height: 56px; font-size: 1.25rem; border-radius: 14px; }
 
            /* ── Divider ── */
            .divider { height: 1px; background: var(--border); margin: 1.5rem 0; }
 
            /* ── Empty state ── */
            .empty-state {
                text-align: center;
                padding: 4rem 2rem;
            }
            .empty-state-icon {
                width: 56px; height: 56px;
                background: var(--bg-3);
                border-radius: 14px;
                display: flex; align-items: center; justify-content: center;
                margin: 0 auto 1rem;
                color: var(--text-3);
            }
            .empty-state h3 { font-family: var(--font-head); font-size: 1.1rem; color: var(--text); margin-bottom: 0.4rem; }
            .empty-state p  { font-size: 0.875rem; color: var(--text-3); margin-bottom: 1.5rem; }
 
            /* ── Pagination override ── */
            nav[role="navigation"] span, nav[role="navigation"] a {
                display: inline-flex; align-items: center; justify-content: center;
                min-width: 32px; height: 32px;
                padding: 0 0.5rem;
                border-radius: 6px;
                font-size: 0.8rem;
                color: var(--text-3);
                border: 1px solid var(--border);
                background: var(--bg-2);
                margin: 0 2px;
                text-decoration: none;
                transition: background 0.15s, color 0.15s;
            }
            nav[role="navigation"] a:hover { background: var(--bg-3); color: var(--text); }
            nav[role="navigation"] span[aria-current] { background: var(--green); color: #fff; border-color: var(--green); }
        </style>
    </head>
    <body>
        <div id="app-shell">
            @include('layouts.navigation')
 
            @if(session('error'))
            <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
                <div class="alert alert-error">{{ session('error') }}</div>
            </div>
            @endif
 
            <main>
                @yield('content')
            </main>
        </div>
 
        <script>lucide.createIcons();</script>
        @stack('scripts')
    </body>
</html>
 