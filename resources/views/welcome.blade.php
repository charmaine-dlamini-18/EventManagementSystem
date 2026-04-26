<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Event Management System') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 flex items-center justify-center">
            @auth
                <div class="text-center">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Welcome!</h1>
                    <a href="{{ url('/dashboard') }}" class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Go to Dashboard</a>
                </div>
            @else
                <div class="text-center">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Event Management System</h1>
                    <div class="flex justify-center gap-4">
                        <a href="{{ route('login') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Log in</a>
                        <a href="{{ route('register') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">Register</a>
                    </div>
                </div>
            @endauth
        </div>
    </body>
</html>
