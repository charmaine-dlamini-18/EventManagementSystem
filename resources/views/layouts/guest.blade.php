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