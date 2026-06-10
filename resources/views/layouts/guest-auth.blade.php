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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900">
    <div class="flex min-h-screen flex-col lg:flex-row">
        <div class="auth-split-left relative flex w-full items-center justify-center overflow-hidden px-6 py-12 lg:w-1/2">
            <div class="absolute inset-0 opacity-10">
                <svg class="h-full w-full" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)" />
                </svg>
            </div>
            <div class="relative z-10 w-full max-w-md text-center text-white lg:text-left">
                <a href="{{ route('welcome') }}" class="mb-8 inline-flex items-center gap-2 text-sm text-white/70 hover:text-white transition">
                    <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                </a>
                <div class="mb-6 flex justify-center lg:justify-start">
                    <picture>
                        <source srcset="{{ asset('images/logo-ekaku.webp') }}" type="image/webp">
                        <img src="{{ asset('images/logo-ekaku.png') }}" alt="e-Kaku" class="h-14 object-contain brightness-0 invert">
                    </picture>
                </div>
                <h1 class="text-3xl font-bold leading-tight md:text-4xl">e-Kaku V3</h1>
                <p class="mt-2 text-lg text-blue-200">Kartu Kuning Digital</p>
                <p class="mt-1 text-sm text-blue-200/70">Dinas Tenaga Kerja Kabupaten Pandeglang</p>
                <div class="mt-8 hidden lg:flex justify-center lg:justify-start">
                    <picture>
                        <source srcset="{{ asset('images/lambang-pandeglang.webp') }}" type="image/webp">
                        <img src="{{ asset('images/lambang-pandeglang.png') }}" alt="Lambang Pandeglang" class="h-28 w-28 animate-float opacity-30">
                    </picture>
                </div>
            </div>
        </div>

        <div class="auth-split-right flex w-full items-center justify-center px-4 py-10 lg:w-1/2 lg:px-8">
            <div class="w-full max-w-md">
                <div class="text-center lg:hidden">
                    <picture>
                        <source srcset="{{ asset('images/logo-ekaku.webp') }}" type="image/webp">
                        <img src="{{ asset('images/logo-ekaku.png') }}" alt="e-Kaku" class="mx-auto mb-6 h-10 object-contain">
                    </picture>
                </div>
                <div class="rounded-2xl border border-gray-100 bg-white shadow-xl shadow-gray-200/50">
                    <div class="px-6 py-8 sm:px-8 sm:py-10">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
