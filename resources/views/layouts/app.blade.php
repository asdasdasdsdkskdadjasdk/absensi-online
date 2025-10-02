<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-t">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"/>
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"/>
        <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
        {{-- Link ini khusus untuk perangkat Apple --}}
        <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
        {{-- ------------------------------------------------ --}}

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-800">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
<!-- 
        @if(Config('app.env') == 'local')
        <script src="//cdn.jsdelivr.net/npm/eruda"></script>
        <script>eruda.init();</script>
        @endif -->

    </body>
</html>

