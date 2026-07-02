<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Auth') - {{ config('app.name', 'e-Kaku V3') }}</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.ico') }}">
    <link rel="preload" href="{{ asset('fa-solid-900.woff2') }}" as="font" type="font/woff2" crossorigin>

    <style>
    @font-face{font-family:"Font Awesome 6 Free";font-style:normal;font-weight:900;font-display:swap;src:url({{ asset('fa-solid-900.woff2') }}) format("woff2")}.fa,.fa-solid,.fas{-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;display:inline-block;font-style:normal;font-variant:normal;line-height:1;text-rendering:auto}.fa-classic,.fa-solid,.fas{font-family:"Font Awesome 6 Free"}.fas{font-weight:900}.fas:before{content:var(--fa)}.fa-fw{text-align:center;width:1.25em}.fa-ul{list-style-type:none;margin-left:2.5em;padding-left:0}.fa-ul>li{position:relative}.fa-li{left:calc(2em * -1);position:absolute;text-align:center;width:2em;line-height:inherit}@keyframes fa-spin{0%{transform:rotate(0deg)}100%{transform:rotate(360deg)}}.fa-spin{animation:fa-spin 1s infinite linear}.fa-address-card{--fa:"\f2be"}.fa-arrow-left{--fa:"\f060"}.fa-arrow-right{--fa:"\f061"}.fa-bars{--fa:"\f0c9"}.fa-bell{--fa:"\f0f3"}.fa-bell-slash{--fa:"\f1f6"}.fa-bolt{--fa:"\f0e7"}.fa-building-columns{--fa:"\f19c"}.fa-calendar-check{--fa:"\f272"}.fa-camera{--fa:"\f030"}.fa-certificate{--fa:"\f0a3"}.fa-chart-line{--fa:"\f201"}.fa-check-circle{--fa:"\f058"}.fa-chevron-down{--fa:"\f078"}.fa-chevron-up{--fa:"\f077"}.fa-circle-check{--fa:"\f058"}.fa-circle-xmark{--fa:"\f057"}.fa-clock-rotate-left{--fa:"\f1da"}.fa-cloud-upload-alt{--fa:"\f0ee"}.fa-database{--fa:"\f1c0"}.fa-envelope{--fa:"\f0e0"}.fa-exclamation-circle{--fa:"\f06a"}.fa-exclamation-triangle{--fa:"\f071"}.fa-eye{--fa:"\f06e"}.fa-eye-slash{--fa:"\f070"}.fa-file{--fa:"\f15b"}.fa-file-alt{--fa:"\f15c"}.fa-file-pdf{--fa:"\f1c1"}.fa-file-upload{--fa:"\f574"}.fa-graduation-cap{--fa:"\f501"}.fa-history{--fa:"\f1da"}.fa-id-card{--fa:"\f2c2"}.fa-info-circle{--fa:"\f05a"}.fa-lock{--fa:"\f023"}.fa-map-marked-alt{--fa:"\f5a0"}.fa-paper-plane{--fa:"\f1d8"}.fa-plus{--fa:"\f067"}.fa-print{--fa:"\f02f"}.fa-qrcode{--fa:"\f029"}.fa-save{--fa:"\f0c7"}.fa-shield-halved{--fa:"\f3ed"}.fa-sign-out-alt{--fa:"\f2f5"}.fa-sort{--fa:"\f0dc"}.fa-sort-down{--fa:"\f0dd"}.fa-sort-up{--fa:"\f0de"}.fa-spinner{--fa:"\f110"}.fa-sync-alt{--fa:"\f2f1"}.fa-tachometer-alt{--fa:"\f3fd"}.fa-times{--fa:"\f00d"}.fa-trash-alt{--fa:"\f2ed"}.fa-triangle-exclamation{--fa:"\f071"}.fa-trophy{--fa:"\f091"}.fa-upload{--fa:"\f093"}.fa-user{--fa:"\f007"}.fa-check-double{--fa:"\f560"}.fa-arrows-rotate{--fa:"\f021"}.fa-table{--fa:"\f0ce"}.fa-user-shield{--fa:"\f505"}.fa-users{--fa:"\f0c0"}.fa-building{--fa:"\f1ad"}.fa-chart-pie{--fa:"\f200"}.fa-check{--fa:"\f00c"}.fa-download{--fa:"\f019"}.fa-file-export{--fa:"\f56e"}.fa-map-marker-alt{--fa:"\f3c5"}.fa-mobile-alt{--fa:"\f3cd"}.fa-phone{--fa:"\f095"}.fa-sign-in-alt{--fa:"\f2f6"}.fa-user-edit{--fa:"\f4ff"}.fa-user-plus{--fa:"\f234"}.fa-chart-bar{--fa:"\f080"}.fa-clipboard-list{--fa:"\f0ae"}.fa-clock{--fa:"\f017"}.fa-file-csv{--fa:"\f163"}.fa-filter{--fa:"\f0b0"}.fa-inbox{--fa:"\f1c0"}.fa-percentage{--fa:"\f541"}.fa-times-circle{--fa:"\f057"}.fa-trash{--fa:"\f2ed"}.fa-user-tie{--fa:"\f508"}
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900">
    <div class="login-gate-wrapper" data-bladeflow-content>
        <div class="login-brand-section auth-split-left relative flex w-full items-center justify-center overflow-hidden px-6 py-12 lg:w-1/2">
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
                        <source srcset="{{ asset('images/lambang-pandeglang.webp') }}" type="image/webp">
                        <img src="{{ asset('images/lambang-pandeglang.png') }}" alt="Lambang Pandeglang" class="h-24 w-24 object-contain" width="96" height="96" loading="eager" fetchpriority="high">
                    </picture>
                </div>
                <h1 class="text-3xl font-bold leading-tight md:text-4xl">e-Kaku V3</h1>
                <p class="mt-2 text-lg text-blue-200">Kartu Kuning Digital</p>
                <p class="mt-1 text-sm text-blue-200/70">Dinas Tenaga Kerja Kabupaten Pandeglang</p>
            </div>
        </div>

        <div class="login-form-section auth-split-right flex w-full items-center justify-center px-4 py-10 lg:w-1/2 lg:px-8">
            <div class="w-full max-w-md">
                <div class="text-center lg:hidden">
                    <picture>
                        <source srcset="{{ asset('images/lambang-pandeglang.webp') }}" type="image/webp">
                        <img src="{{ asset('images/lambang-pandeglang.png') }}" alt="Lambang Pandeglang" class="mx-auto mb-6 h-16 w-16 object-contain" width="64" height="64" loading="eager">
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
    <script>
    (function() {
        var ANIM_MS = 600;
        var form = document.querySelector('form[action]');
        if (!form) return;
        var btn = form.querySelector('button[type="submit"]');
        var originalHtml = btn ? btn.innerHTML : '';

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            }

            document.body.classList.add('login-gate-open');

            var formData = new FormData(form);
            var dashboardHTML = null;
            var loginFailed = false;
            var lastDashboardUrl = '/dashboard/user';

            fetch(form.action, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            }).then(function(res) {
                if (res.redirected) {
                    lastDashboardUrl = new URL(res.url).pathname;
                    return res.text();
                }
                return res.text().then(function(html) {
                    if (html.includes('error') || html.includes('password') || html.includes('validation')) {
                        loginFailed = true;
                        showError(html);
                    } else {
                        return html;
                    }
                });
            }).then(function(html) {
                if (html && !loginFailed) {
                    dashboardHTML = html;
                }
            }).catch(function() {
                loginFailed = true;
                document.body.classList.remove('login-gate-open');
                if (btn) { btn.disabled = false; btn.innerHTML = originalHtml; }
            });

            function showError(html) {
                document.body.classList.remove('login-gate-open');
                if (btn) { btn.disabled = false; btn.innerHTML = originalHtml; }
                var tmp = document.createElement('div');
                tmp.innerHTML = html;
                var errEl = tmp.querySelector('.text-sm.text-red-600');
                if (errEl) {
                    var slot = form.closest('.px-6, .sm\\:px-8');
                    if (slot) {
                        var existing = slot.querySelector('.mb-4.rounded-lg.bg-red-50');
                        if (existing) existing.remove();
                        var alert = document.createElement('div');
                        alert.className = 'mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-600';
                        alert.textContent = errEl.textContent.trim();
                        slot.insertBefore(alert, form);
                    }
                }
            }

            setTimeout(function() {
                if (loginFailed) return;
                if (dashboardHTML) {
                    document.open();
                    document.write(dashboardHTML);
                    document.close();
                    var match = dashboardHTML.match(/<title[^>]*>([^<]+)<\/title>/i);
                    var title = match ? match[1] : 'Dashboard';
                    document.title = title;
                    history.pushState({}, '', lastDashboardUrl || '/dashboard/user');
                }
            }, ANIM_MS);
        });
    })();
    </script>
    @bladeflowEngine
</body>
</html>
