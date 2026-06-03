<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Developer Service Desk</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="antialiased font-sans bg-gradient-to-br from-slate-50 via-white to-pink-50">
        <div class="min-h-screen">
            <div class="max-w-[1400px] mx-auto px-6 py-16">
                <div>
                    @include('welcome_variants.v1')
                </div>
            </div>
        </div>
        @livewireScripts
    </body>
</html>
