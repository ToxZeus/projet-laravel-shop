<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-indigo-50 via-white to-gray-100 dark:from-gray-950 dark:via-gray-900 dark:to-gray-900">
            <div class="flex items-center gap-2">
                <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-lg shadow-indigo-600/30">
                    <x-icon name="sparkles" class="w-7 h-7" />
                </span>
                <span class="font-bold text-2xl text-gray-800 dark:text-gray-100">{{ config('app.name', 'Shop') }}</span>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white dark:bg-gray-800 shadow-xl ring-1 ring-gray-900/5 dark:ring-white/10 overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
