<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://unpkg.com/lucide@latest"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900">
        <div class="min-h-screen">
            @include('layouts.navigation')
            
            
            @if(session('error'))
            <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
                <div class="bg-red-500 text-white px-4 py-3 rounded-lg font-medium" role="alert">
                    ERROR: {{ session('error') }}
                </div>
            </div>
            @endif
            
            <main>
                @yield('content')
            </main>
        </div>
        <script>
            lucide.createIcons();
        </script>
        @stack('scripts')
    </body>
</html>
