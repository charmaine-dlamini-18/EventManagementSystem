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
 