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