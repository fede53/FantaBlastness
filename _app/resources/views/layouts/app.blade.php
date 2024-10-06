<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-white">
    <div class="bg-image">
        <img src="/assets/images/linea.png">
    </div>
<div class="min-h-screen bg-dark dark:bg-dark">
    @include('layouts.navigation')

    <!-- Page Heading -->
    @isset($header)
        <header class="bg-dark-100 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 name-page">
                {{ $header }}
            </div>
        </header>
    @endisset

    <!-- Breadcrumbs Slot -->
    @isset($breadcrumbs)
        <!-- <nav class="bg-dark-100 py-3 dark:bg-black">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <ul class="flex items-center space-x-2 text-sm text-white">
                    {{ $breadcrumbs }}
                </ul>
            </div>
        </nav> -->
    @endisset

    <!-- Flash Messages -->
    <div id="flash-message-container" class="fixed bottom-4 right-4 z-50">
        @if (session('success'))
            <div id="flash-message" class="bg-green-500 text-white py-3 px-5 rounded-lg shadow-lg transition-opacity duration-500 ease-in-out opacity-100">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div id="flash-message" class="bg-red-500 text-white py-3 px-5 rounded-lg shadow-lg transition-opacity duration-500 ease-in-out opacity-100">
                {{ session('error') }}
            </div>
        @endif
    </div>

    <!-- Page Content -->
    <main>
        {{ $slot }}
    </main>
</div>

<!-- Script to auto-hide flash messages -->
@stack('scripts')

</body>
</html>
