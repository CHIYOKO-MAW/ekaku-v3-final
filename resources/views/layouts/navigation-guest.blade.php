<nav class="sticky top-0 z-50 border-b border-gray-200 bg-white">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
        <a href="{{ route('welcome') }}" class="flex items-center gap-2">
            <img src="{{ asset('images/logo-ekaku.png') }}" alt="e-Kaku" class="h-8 w-auto">
        </a>

        <div class="hidden items-center gap-6 sm:flex">
            <a href="{{ route('welcome') }}" class="text-sm {{ request()->routeIs('welcome') ? 'font-semibold text-disnaker-600' : 'text-gray-600 hover:text-disnaker-600' }}">Beranda</a>
            <a href="#features" class="text-sm text-gray-600 hover:text-disnaker-600">Fitur</a>
            <a href="#about" class="text-sm text-gray-600 hover:text-disnaker-600">Tentang</a>
            <a href="#contact" class="text-sm text-gray-600 hover:text-disnaker-600">Kontak</a>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('login') }}" class="px-3 py-2 text-sm font-medium {{ request()->routeIs('login') ? 'text-disnaker-600' : 'text-gray-600 hover:text-gray-900' }}">Masuk</a>
            <a href="{{ route('register') }}" class="rounded-md bg-disnaker-500 px-4 py-2 text-sm font-medium text-white hover:bg-disnaker-600">Daftar</a>
        </div>
    </div>
</nav>
