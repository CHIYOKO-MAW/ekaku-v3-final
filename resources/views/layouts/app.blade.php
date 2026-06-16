<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description', 'e-Kaku V3 - Sistem Digital Kartu Kuning (AK1) Dinas Tenaga Kerja Kabupaten Pandeglang. Kelola biodata, upload berkas, verifikasi admin, dan cetak kartu kuning secara online.')">

    <title>{{ config('app.name', 'e-Kaku V3') }} - @yield('title', 'Digital Yellow Card')</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap"></noscript>

    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/webfonts/fa-solid-900.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"></noscript>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans text-gray-900">
    <div id="app">
        @if (View::hasSection('navigation'))
            @yield('navigation')
        @elseif (auth()->check() && auth()->user()->role === 'atasan')
            @include('layouts.navigation-atasan')
        @elseif (auth()->check() && auth()->user()->role === 'admin')
            @include('layouts.navigation-admin')
        @elseif (auth()->check())
            @include('layouts.navigation-user')
        @else
            @include('layouts.navigation-guest')
        @endif

        <main class="min-h-screen">
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" class="mx-auto mt-4 max-w-7xl rounded-md border border-green-300 bg-green-50 px-4 py-3 text-green-800" role="alert">
                    <button @click="show = false" class="float-right text-green-600 hover:text-green-800">&times;</button>
                    <strong class="font-semibold">Sukses:</strong> {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" class="mx-auto mt-4 max-w-7xl rounded-md border border-red-300 bg-red-50 px-4 py-3 text-red-800" role="alert">
                    <button @click="show = false" class="float-right text-red-600 hover:text-red-800">&times;</button>
                    <strong class="font-semibold">Error:</strong> {{ session('error') }}
                </div>
            @endif

            @if (session('info'))
                <div x-data="{ show: true }" x-show="show" class="mx-auto mt-4 max-w-7xl rounded-md border border-blue-300 bg-blue-50 px-4 py-3 text-blue-800" role="alert">
                    <button @click="show = false" class="float-right text-blue-600 hover:text-blue-800">&times;</button>
                    <strong class="font-semibold">Info:</strong> {{ session('info') }}
                </div>
            @endif

            {{ $slot ?? '' }}
            @yield('content')
        </main>

        <footer class="border-t border-gray-200 bg-white py-6">
            <div class="mx-auto max-w-7xl px-4 text-center text-sm text-gray-600 sm:px-6 lg:px-8">
                <div class="mb-3 flex items-center justify-center gap-2">
                    <picture>
                        <source srcset="{{ asset('images/lambang-pandeglang.webp') }}" type="image/webp">
                        <img src="{{ asset('images/lambang-pandeglang.png') }}" alt="Lambang Pandeglang" class="h-8 w-8">
                    </picture>
                    <span class="font-semibold text-gray-700">Dinas Tenaga Kerja Kabupaten Pandeglang</span>
                </div>
                <p>&copy; {{ date('Y') }} Disnaker Pandeglang. All rights reserved.</p>
                <p class="mt-1 text-xs text-gray-500">e-Kaku V3 — Digital Yellow Card System</p>
            </div>
        </footer>
    </div>

    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('scrolled');
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('.scroll-reveal').forEach(el => observer.observe(el));
        });
    </script>

</body>
</html>
