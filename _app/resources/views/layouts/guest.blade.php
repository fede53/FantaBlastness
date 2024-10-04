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
    <body class="font-sans text-white antialiased text-white">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-dark dark:bg-dark">
            <div class="bg-video">
                
                <video autoplay loop>
                    <source src="/assets/images/video9.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
            <main>
                

                <div class="cnt-login w-full sm:max-w-md mt-6 px-6 py-4 bg-dark-100 shadow-md overflow-hidden sm:rounded-lg">
                    <div class="logo-login">
                        <a href="/">
                            <x-application-logo class="w-20 h-20 fill-current text-white" />
                        </a>
                    </div>
                    {{ $slot }}
                </div>
            </main>
        </div>
    </body>
</html>
