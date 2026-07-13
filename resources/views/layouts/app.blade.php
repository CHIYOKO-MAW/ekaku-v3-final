<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description', 'e-Kaku V3 - Sistem Digital Kartu Kuning (AK1) Dinas Tenaga Kerja Kabupaten Pandeglang. Kelola biodata, upload berkas, verifikasi admin, dan cetak kartu kuning secara online.')">

    <title>{{ config('app.name', 'e-Kaku V3') }} - @yield('title', 'Digital Yellow Card')</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.ico') }}">
    <link rel="preload" href="{{ asset('fa-solid-900.woff2') }}" as="font" type="font/woff2" crossorigin>

    <style>
    @font-face{font-family:"Font Awesome 6 Free";font-style:normal;font-weight:900;font-display:swap;src:url({{ asset('fa-solid-900.woff2') }}) format("woff2")}.fa,.fa-solid,.fas{-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;display:inline-block;font-style:normal;font-variant:normal;line-height:1;text-rendering:auto}.fa-classic,.fa-solid,.fas{font-family:"Font Awesome 6 Free"}.fas{font-weight:900}.fas:before{content:var(--fa)}.fa-fw{text-align:center;width:1.25em}.fa-ul{list-style-type:none;margin-left:2.5em;padding-left:0}.fa-ul>li{position:relative}.fa-li{left:calc(2em * -1);position:absolute;text-align:center;width:2em;line-height:inherit}@keyframes fa-spin{0%{transform:rotate(0deg)}100%{transform:rotate(360deg)}}.fa-spin{animation:fa-spin 1s infinite linear}.fa-address-card{--fa:"\f2be"}.fa-arrow-left{--fa:"\f060"}.fa-arrow-right{--fa:"\f061"}.fa-arrow-up{--fa:"\f062"}.fa-bars{--fa:"\f0c9"}.fa-bell{--fa:"\f0f3"}.fa-bell-slash{--fa:"\f1f6"}.fa-bolt{--fa:"\f0e7"}.fa-building-columns{--fa:"\f19c"}.fa-calendar-check{--fa:"\f272"}.fa-camera{--fa:"\f030"}.fa-certificate{--fa:"\f0a3"}.fa-chart-line{--fa:"\f201"}.fa-check-circle{--fa:"\f058"}.fa-chevron-down{--fa:"\f078"}.fa-chevron-up{--fa:"\f077"}.fa-circle-check{--fa:"\f058"}.fa-circle-xmark{--fa:"\f057"}.fa-clock-rotate-left{--fa:"\f1da"}.fa-cloud-upload-alt{--fa:"\f0ee"}.fa-database{--fa:"\f1c0"}.fa-envelope{--fa:"\f0e0"}.fa-exclamation-circle{--fa:"\f06a"}.fa-exclamation-triangle{--fa:"\f071"}.fa-eye{--fa:"\f06e"}.fa-eye-slash{--fa:"\f070"}.fa-file{--fa:"\f15b"}.fa-file-alt{--fa:"\f15c"}.fa-file-pdf{--fa:"\f1c1"}.fa-file-upload{--fa:"\f574"}.fa-graduation-cap{--fa:"\f501"}.fa-history{--fa:"\f1da"}.fa-id-card{--fa:"\f2c2"}.fa-info-circle{--fa:"\f05a"}.fa-lock{--fa:"\f023"}.fa-list{--fa:"\f0ca"}.fa-map-marked-alt{--fa:"\f5a0"}.fa-paper-plane{--fa:"\f1d8"}.fa-plus{--fa:"\f067"}.fa-print{--fa:"\f02f"}.fa-qrcode{--fa:"\f029"}.fa-save{--fa:"\f0c7"}.fa-shield-halved{--fa:"\f3ed"}.fa-sign-out-alt{--fa:"\f2f5"}.fa-sort{--fa:"\f0dc"}.fa-sort-down{--fa:"\f0dd"}.fa-sort-up{--fa:"\f0de"}.fa-spinner{--fa:"\f110"}.fa-sync-alt{--fa:"\f2f1"}.fa-tachometer-alt{--fa:"\f3fd"}.fa-times{--fa:"\f00d"}.fa-trash-alt{--fa:"\f2ed"}.fa-triangle-exclamation{--fa:"\f071"}.fa-trophy{--fa:"\f091"}.fa-upload{--fa:"\f093"}.fa-user{--fa:"\f007"}.fa-check-double{--fa:"\f560"}.fa-arrows-rotate{--fa:"\f021"}.fa-table{--fa:"\f0ce"}.fa-user-shield{--fa:"\f505"}.fa-users{--fa:"\f0c0"}.fa-building{--fa:"\f1ad"}.fa-chart-pie{--fa:"\f200"}.fa-check{--fa:"\f00c"}.fa-download{--fa:"\f019"}.fa-file-export{--fa:"\f56e"}.fa-map-marker-alt{--fa:"\f3c5"}.fa-mobile-alt{--fa:"\f3cd"}.fa-phone{--fa:"\f095"}.fa-sign-in-alt{--fa:"\f2f6"}.fa-user-edit{--fa:"\f4ff"}.fa-user-plus{--fa:"\f234"}.fa-chart-bar{--fa:"\f080"}.fa-clipboard-list{--fa:"\f0ae"}.fa-clock{--fa:"\f017"}.fa-file-csv{--fa:"\f163"}.fa-filter{--fa:"\f0b0"}.fa-inbox{--fa:"\f1c0"}.fa-percentage{--fa:"\f541"}.fa-times-circle{--fa:"\f057"}.fa-trash{--fa:"\f2ed"}.fa-user-tie{--fa:"\f508"}
    </style>

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

        <main class="min-h-screen pb-20 sm:pb-0">
            @if (session('success'))
                <div id="flash-success" data-message="{{ session('success') }}" x-data></div>
            @endif
            @if (session('error'))
                <div id="flash-error" data-message="{{ session('error') }}" x-data></div>
            @endif
            @if (session('info'))
                <div id="flash-info" data-message="{{ session('info') }}" x-data></div>
            @endif
            @if (session('warning'))
                <div id="flash-warning" data-message="{{ session('warning') }}" x-data></div>
            @endif

            {{ $slot ?? '' }}
            @yield('content')
        </main>

        <footer class="border-t border-gray-200 bg-white py-6 pb-24 sm:pb-6">
            <div class="mx-auto max-w-7xl px-4 text-center text-sm text-gray-600 sm:px-6 lg:px-8">
                <div class="mb-3 flex items-center justify-center gap-2">
                    <picture>
                        <source srcset="{{ asset('images/lambang-pandeglang.webp') }}" type="image/webp">
                        <img src="{{ asset('images/lambang-pandeglang.png') }}" alt="Lambang Pandeglang" class="h-8 w-8" width="32" height="32" loading="lazy">
                    </picture>
                    <span class="font-semibold text-gray-700">Dinas Tenaga Kerja Kabupaten Pandeglang</span>
                </div>
                <p>&copy; {{ date('Y') }} Disnaker Pandeglang. All rights reserved.</p>
                <p class="mt-1 text-xs text-gray-500">e-Kaku V3 — Digital Yellow Card System</p>
            </div>
        </footer>

        {{-- Back to Top Button --}}
        <button x-data="{ show: false }" x-on:scroll.window="show = window.scrollY > 300" x-show="show" x-cloak x-on:click="window.scrollTo({ top: 0, behavior: 'smooth' })" class="fixed bottom-24 right-4 z-40 flex h-11 w-11 items-center justify-center rounded-full bg-disnaker-600 text-white shadow-lg transition-all hover:bg-disnaker-700 hover:shadow-xl sm:bottom-8 sm:right-8" aria-label="Kembali ke atas">
            <i class="fas fa-arrow-up"></i>
        </button>
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
