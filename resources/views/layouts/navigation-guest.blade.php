<nav x-data="{ scrolled: false, menuOpen: false }" x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 20)" class="sticky top-0 z-50 transition-all duration-300" x-bind:class="scrolled ? 'border-b border-gray-200 bg-white/95 backdrop-blur-sm' : 'bg-white'">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
        <a href="{{ route('welcome') }}" class="flex items-center gap-2">
            <picture>
                <source srcset="{{ asset('images/logo-ekaku.webp') }}" type="image/webp">
                <img src="{{ asset('images/logo-ekaku.png') }}" alt="e-Kaku" class="h-8 w-auto" loading="eager" width="128" height="32">
            </picture>
        </a>

        {{-- Desktop nav links --}}
        <div class="hidden items-center gap-6 sm:flex">
            <a href="{{ route('welcome') }}" class="text-sm {{ request()->routeIs('welcome') ? 'font-semibold text-disnaker-600' : 'text-gray-600 hover:text-disnaker-600' }}">Beranda</a>
            <a href="#features" class="text-sm text-gray-600 hover:text-disnaker-600">Fitur</a>
            <a href="#how-it-works" class="text-sm text-gray-600 hover:text-disnaker-600">Cara Kerja</a>
            <a href="#about" class="text-sm text-gray-600 hover:text-disnaker-600">Tentang</a>
        </div>

        {{-- Desktop auth buttons --}}
        <div class="hidden items-center gap-2 sm:flex">
            <a href="{{ route('login') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('login') ? 'text-disnaker-600' : 'text-gray-600 hover:text-gray-900' }}">Masuk</a>
            <a href="{{ route('register') }}" class="rounded-md bg-disnaker-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-disnaker-600">Daftar</a>
        </div>

        {{-- Mobile hamburger --}}
        <button x-on:click="menuOpen = !menuOpen" class="sm:hidden rounded-md p-2 text-gray-600 hover:bg-gray-100" aria-label="Menu">
            <svg x-show="!menuOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            <svg x-show="menuOpen" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- Mobile dropdown menu --}}
    <div x-show="menuOpen" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="sm:hidden border-t border-gray-100 bg-white px-4 pb-4 pt-2">
        <div class="flex flex-col gap-1">
            <a href="{{ route('welcome') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Beranda</a>
            <a href="#features" x-on:click="menuOpen = false" class="rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Fitur</a>
            <a href="#how-it-works" x-on:click="menuOpen = false" class="rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cara Kerja</a>
            <a href="#about" x-on:click="menuOpen = false" class="rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Tentang</a>
        </div>
        <div class="mt-3 flex gap-2">
            <a href="{{ route('login') }}" class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2 text-center text-sm font-medium text-gray-700 hover:bg-gray-50">Masuk</a>
            <a href="{{ route('register') }}" class="flex-1 rounded-lg bg-disnaker-500 px-4 py-2 text-center text-sm font-medium text-white hover:bg-disnaker-600">Daftar</a>
        </div>
    </div>
</nav>
