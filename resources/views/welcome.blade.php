<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Event Management System') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
        <script src="https://unpkg.com/lucide@latest"></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
           * { box-sizing: border-box; margin: 0; padding: 0; }

            body {
                font-family: 'DM Sans', sans-serif;
                min-height: 100vh;
                overflow: hidden;
            }

        </style>
    </head>
    <body>

        {{-- Video Background --}}
        <div class="ems-video-bg">
            <video autoplay muted loop playsinline>
                <source src="{{ asset('videos/background.mp4') }}" type="video/mp4">
            </video>
        </div>

        <div class="ems-page">

            {{-- Logo --}}
            <a href="/" class="ems-logo">
                <div class="ems-logo-icon">
                    <i data-lucide="calendar" class="w-6 h-6"></i>
                </div>
                <span class="ems-logo-text">EMS</span>
            </a>

            @auth
                {{-- Logged-in user: go to dashboard --}}
                <div class="ems-card">
                    <h1>Welcome back,<br>{{ Auth::user()->name }}!</h1>
                    <p>You're signed in as {{ Auth::user()->role_label ?? Auth::user()->role }}.</p>
                    <a href="{{ url('/dashboard') }}" class="btn-primary">
                        Go to Dashboard →
                    </a>
                </div>
            @else
                {{-- Guest: hero + CTA --}}
                <div class="hero-badge">
                    <span>✦ Role-based Event Platform</span>
                </div>

                <h1 class="hero-title">
                    Manage Events<br><span>Effortlessly</span>
                </h1>

                <p class="hero-subtitle">
                    Create, discover, and register for events. Built for organizers, attendees, and admins.
                </p>

                <div class="btn-group">
                    <a href="{{ route('login') }}" class="btn-primary">
                        <i data-lucide="log-in" class="w-4 h-4"></i>
                        Log In
                    </a>
                    <a href="{{ route('register') }}" class="btn-secondary">
                        Create an Account
                    </a>
                </div>

                <div class="feature-pills">
                    <span class="pill"><i data-lucide="check" class="w-3 h-3"></i> Free to join</span>
                    <span class="pill"><i data-lucide="shield" class="w-3 h-3"></i> Role-based access</span>
                    <span class="pill"><i data-lucide="bell" class="w-3 h-3"></i> Email notifications</span>
                </div>
            @endauth

        </div>

        <script>lucide.createIcons();</script>
    </body>
</html>