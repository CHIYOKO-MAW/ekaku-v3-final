@php
    $currentUser = auth()->user();
    $unreadNotes = $unreadNotes ?? $currentUser->notificationsE()->where('status_baca', false)->count();
    $latestNotes = $latestNotes ?? $currentUser->notificationsE()->latest()->take(5)->get();
@endphp

<nav class="sticky top-0 z-50 border-b border-gray-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <a href="{{ route('atasan.dashboard') }}" class="flex items-center gap-2">
                <picture>
                    <source srcset="{{ asset('images/logo-ekaku.webp') }}" type="image/webp">
                    <img src="{{ asset('images/logo-ekaku.png') }}" alt="e-Kaku" class="h-8 w-auto" loading="eager" width="128" height="32">
                </picture>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="ml-auto sm:hidden">
                @csrf
                <button type="submit" class="rounded-md p-2 text-gray-400 hover:text-red-500 hover:bg-gray-100 transition-colors" title="Keluar">
                    <i class="fas fa-sign-out-alt text-lg"></i>
                </button>
            </form>

            <div class="hidden items-center gap-6 sm:flex">
                <a href="{{ route('atasan.dashboard') }}" class="inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium {{ request()->routeIs('atasan.dashboard') ? 'border-disnaker-500 text-disnaker-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">Dashboard</a>
                <a href="{{ route('atasan.rekap-admin') }}" class="inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium {{ request()->routeIs('atasan.rekap-admin*') ? 'border-disnaker-500 text-disnaker-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">Rekap Admin</a>
            </div>

            <div class="hidden items-center sm:flex">
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" aria-label="Notifikasi" class="relative rounded-full p-2 text-gray-500 transition hover:bg-gray-100 hover:text-disnaker-600">
                        <i class="fas fa-bell text-lg"></i>
                        @if ($unreadNotes > 0)
                            <span class="absolute -right-0.5 -top-0.5 inline-flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-red-500 px-1 text-xs text-white">
                                {{ $unreadNotes > 9 ? '9+' : $unreadNotes }}
                            </span>
                        @endif
                    </button>
                    <div x-cloak x-show="open" @click.away="open = false" class="absolute right-0 z-50 mt-2 w-80 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg">
                        <div class="border-b border-gray-100 px-4 py-3">
                            <p class="font-semibold text-gray-900">Notifikasi</p>
                        </div>
                        <div class="max-h-80 overflow-y-auto">
                            @forelse ($latestNotes as $note)
                                <div class="border-b border-gray-100 px-4 py-3 {{ $note->status_baca ? 'bg-white' : 'bg-blue-50' }}">
                                    <p class="text-sm font-medium text-gray-900">{{ $note->judul }}</p>
                                    <p class="mt-1 text-xs text-gray-600">{{ $note->pesan }}</p>
                                    <p class="mt-1 text-xs text-gray-500">{{ $note->created_at?->diffForHumans() }}</p>
                                </div>
                            @empty
                                <p class="px-4 py-6 text-center text-sm text-gray-500">Belum ada notifikasi.</p>
                            @endforelse
                        </div>
                        <a href="{{ route('notifikasi.index', ['mark_read' => 1]) }}" class="block border-t border-gray-100 px-4 py-3 text-sm font-medium text-disnaker-600 hover:bg-gray-50">
                            Lihat semua notifikasi
                        </a>
                    </div>
                </div>

                <div class="ml-3 relative" x-data="{ open: false }">
                    <button @click="open = !open" class="rounded-full bg-disnaker-500 p-0.5 text-white ring-disnaker-500 transition focus:outline-none focus:ring-2 focus:ring-offset-2">
                        @if ($currentUser->profile_photo_url)
                            <img src="{{ $currentUser->profile_photo_url }}" alt="Avatar" class="h-8 w-8 rounded-full object-cover" loading="eager" width="32" height="32">
                        @else
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-disnaker-500 text-sm font-semibold">
                                {{ strtoupper(substr($currentUser->name, 0, 1)) }}
                            </span>
                        @endif
                    </button>
                    <div x-cloak x-show="open" @click.away="open = false" class="absolute right-0 z-50 mt-2 w-56 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg">
                        <div class="border-b border-gray-100 px-4 py-3">
                            <p class="text-sm font-semibold text-gray-900">{{ $currentUser->name }}</p>
                            <p class="text-xs text-gray-500">{{ $currentUser->email }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-user mr-2"></i> Profil Saya
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Mobile Bottom Navigation --}}
    <div class="bottom-nav sm:hidden">
        <a href="{{ route('atasan.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('atasan.dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i>
        </a>
        <a href="{{ route('atasan.rekap-admin') }}" class="bottom-nav-item {{ request()->routeIs('atasan.rekap-admin*') ? 'active' : '' }}">
            <i class="fas fa-file-alt"></i>
        </a>
        <a href="{{ route('profile.edit') }}" class="bottom-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="fas fa-user"></i>
        </a>
    </div>
</nav>
