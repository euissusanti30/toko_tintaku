<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100">

<div class="min-h-screen">

    <!-- NAVIGATION -->
    <nav x-data="{ open: false }" class="bg-white border-b border-gray-200">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex justify-between h-16">

                <!-- LOGO -->
                <div class="flex items-center">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('img/logotintaku.jpeg') }}"
                             alt="Logo"
                             class="h-10 w-auto">
                    </a>
                </div>

                <!-- MENU DESKTOP -->
                <div class="hidden sm:flex space-x-6 items-center">

                    <a href="/" class="text-gray-700 hover:text-blue-500">Home</a>
                    <a href="/shop" class="text-gray-700 hover:text-blue-500">Shop</a>
                    <a href="/cart" class="text-gray-700 hover:text-blue-500">Cart</a>
                    <a href="/checkout" class="text-gray-700 hover:text-blue-500">Checkout</a>
                    <a href="/loginbackend" class="text-gray-700 hover:text-blue-500 font-semibold">Admin</a>

                </div>

                <!-- MOBILE BUTTON -->
                <div class="sm:hidden flex items-center">
                    <button @click="open = !open" class="text-gray-600 text-2xl">
                        ☰
                    </button>
                </div>

            </div>
        </div>

        <!-- MOBILE MENU -->
        <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden px-4 pb-3">

            <a href="/" class="block py-2">Home</a>
            <a href="/shop" class="block py-2">Shop</a>
            <a href="/cart" class="block py-2">Cart</a>
            <a href="/checkout" class="block py-2">Checkout</a>
            <a href="/loginbackend" class="block py-2 font-semibold">Admin</a>

        </div>

    </nav>

    <!-- PAGE HEADER -->
    @isset($header)
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endisset

    <!-- PAGE CONTENT -->
    <main>
        {{ $slot }}
    </main>

</div>

</body>
</html>