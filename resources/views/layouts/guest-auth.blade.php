<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Auth') - {{ config('app.name', 'e-Kaku V3') }}</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900">
<main class="gradient-bg min-h-screen">
    <div class="relative mx-auto flex min-h-screen max-w-7xl items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            <a href="{{ route('welcome') }}" class="mb-4 flex items-center gap-2 text-sm text-white/80 hover:text-white transition">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>
            <div class="rounded-2xl bg-white p-6 shadow-xl sm:p-8">
                <div class="mb-6 text-center">
                    <img src="{{ asset('images/logo-ekaku.png') }}" alt="e-Kaku" class="mx-auto mb-4 h-12 object-contain">
                    <p class="text-sm text-gray-600">Pelayanan Kartu Kuning Digital Disnaker Pandeglang</p>
                </div>
                {{ $slot }}
            </div>
        </div>
    </div>
</main>
</body>
</html>
