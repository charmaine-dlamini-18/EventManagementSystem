<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&display=swap" rel="stylesheet">

        <script src="https://unpkg.com/lucide@latest"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* ── Video background ── */
            .ems-video-bg {
                position: fixed;
                inset: 0;
                z-index: 0;
                overflow: hidden;
            }
            .ems-video-bg video {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .ems-video-bg::after {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(
                    135deg,
                    rgba(0, 0, 0, 0.78) 0%,
                    rgba(5, 30, 10, 0.72) 50%,
                    rgba(0, 0, 0, 0.85) 100%
                );
            }
            /* ── Page shell ── */
            .ems-page {
                position: relative;
                z-index: 10;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 1.5rem 1rem;
            }
            * ── Logo ── */
            .ems-logo {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                margin-bottom: 1.5rem;
                text-decoration: none;
            }
            .ems-logo-icon {
                width: 48px;
                height: 48px;
                background: #16a34a;
                border-radius: 14px;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 0 0 3px rgba(22,163,74,0.25), 0 8px 24px rgba(22,163,74,0.35);
            }
            .ems-logo-icon svg { color: #fff; }
            .ems-logo-text {
                font-family: 'Syne', sans-serif;
                font-weight: 800;
                font-size: 1.6rem;
                color: #fff;
                letter-spacing: -0.03em;
            }

            /* ── Card ── */
            .ems-card {
                width: 100%;
                max-width: 448px;
                background: rgba(255, 255, 255, 0.06);
                border: 1px solid rgba(255, 255, 255, 0.13);
                border-radius: 20px;
                padding: 2.25rem 2rem;
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                box-shadow:
                    0 0 0 1px rgba(255,255,255,0.04) inset,
                    0 24px 64px rgba(0,0,0,0.55);
                animation: ems-slide-up 0.55s cubic-bezier(0.16,1,0.3,1) both;
            }
            @keyframes ems-slide-up {
                from { opacity: 0; transform: translateY(24px) scale(0.98); }
                to   { opacity: 1; transform: translateY(0)    scale(1);    }
            }
            
            /* ── Override Breeze label colours ── */
            .ems-card label,
            .ems-card .block.text-sm.font-medium {
                color: rgba(255,255,255,0.55) !important;
                font-size: 0.76rem !important;
                letter-spacing: 0.07em !important;
                text-transform: uppercase !important;
            }

            /* ── Override Breeze text inputs ── */
            .ems-card input[type="text"],
            .ems-card input[type="email"],
            .ems-card input[type="password"],
            .ems-card select {
                background: rgba(255,255,255,0.08) !important;
                border: 1px solid rgba(255,255,255,0.15) !important;
                border-radius: 10px !important;
                color: #fff !important;
                font-size: 0.92rem !important;
                padding: 0.7rem 0.9rem !important;
                transition: border-color 0.2s, background 0.2s, box-shadow 0.2s !important;
            }

            .ems-card input::placeholder { color: rgba(255,255,255,0.25) !important; }
            .ems-card input:focus,
            .ems-card select:focus {
                border-color: #4ade80 !important;
                background: rgba(255,255,255,0.11) !important;
                box-shadow: 0 0 0 3px rgba(74,222,128,0.2) !important;
                outline: none !important;
            }

             /* ── Role select ── */
            .ems-card select {
                color: rgba(255,255,255,0.85) !important;
            }
            .ems-card select option {
                background: #111827;
                color: #fff;
            }

             /* ── Checkbox ── */
            .ems-card input[type="checkbox"] {
                accent-color: #4ade80;
                background: transparent !important;
                border: 1px solid rgba(255,255,255,0.3) !important;
                padding: 0 !important;
                width: 15px !important;
                height: 15px !important;
            }
            .ems-card label span,
            .ems-card .ms-2 {
                color: rgba(255,255,255,0.5) !important;
            }

            /* ── Error messages ── */
            .ems-card [class*="text-red"] { color: #f87171 !important; }

            /* ── "Forgot password" link ── */
            .ems-card a[href*="password"] {
                color: #4ade80 !important;
                transition: opacity 0.2s;
            }
            .ems-card a[href*="password"]:hover { opacity: 0.7; }

            /* ── Bottom links (register / sign in) ── */
            .ems-card p.text-center a {
                color: #4ade80 !important;
            }
            .ems-card p.text-center { color: rgba(255,255,255,0.4) !important; }
 
            /* ── Helper text ── */
            .ems-card .text-xs.text-gray-400 { color: rgba(255,255,255,0.3) !important; }

            /* ── PRIMARY BUTTON — bold, impossible to miss ── */
            .ems-card button[type="submit"],
            .ems-card .ems-btn-primary {
                background: #16a34a !important;
                color: #fff !important;
                font-family: 'Syne', sans-serif !important;
                font-weight: 700 !important;
                font-size: 0.95rem !important;
                letter-spacing: 0.04em !important;
                border: none !important;
                border-radius: 10px !important;
                padding: 0.8rem 1.25rem !important;
                cursor: pointer !important;
                box-shadow: 0 4px 20px rgba(22,163,74,0.5) !important;
                transition: transform 0.15s, box-shadow 0.15s, background 0.15s !important;
                width: 100% !important;
            }
            .ems-card button[type="submit"]:hover {
                background: #15803d !important;
                transform: translateY(-2px) !important;
                box-shadow: 0 8px 28px rgba(22,163,74,0.65) !important;
            }
            .ems-card button[type="submit"]:active {
                transform: translateY(0) !important;
                box-shadow: 0 2px 10px rgba(22,163,74,0.4) !important;
            }

            /* ── Session status ── */
            .ems-card [class*="bg-green-"][class*="border-green-"] {
                background: rgba(74,222,128,0.1) !important;
                border-color: rgba(74,222,128,0.3) !important;
                color: #4ade80 !important;
                border-radius: 8px !important;
            }

        </style>
    </head>
    <body class="font-sans antialiased">

        {{-- Video Background --}}
        <div class="ems-video-bg">
            <video autoplay muted loop playsinline>
                {{--
                    Place your video at: public/videos/background.mp4
                    Then run: php artisan storage:link  (if needed)
                --}}
                <source src="{{ asset('videos/background.mp4') }}" type="video/mp4">
            </video>
        </div>

        {{-- Page Content --}}
        <div class="ems-page">

            {{-- Logo --}}
            <a href="/" class="ems-logo">
                <div class="ems-logo-icon">
                    <i data-lucide="calendar" class="w-6 h-6"></i>
                </div>
                <span class="ems-logo-text">EMS</span>
            </a>

            {{-- Auth Card --}}
            <div class="ems-card">
                {{ $slot }}
            </div>

        </div>

        <script>lucide.createIcons();</script>
    </body>
</html>