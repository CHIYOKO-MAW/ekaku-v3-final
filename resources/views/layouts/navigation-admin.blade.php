@php
    $currentUser = auth()->user();
    $unreadNotes = $unreadNotes ?? $currentUser->notificationsE()->where('status_baca', false)->count();
    $latestNotes = $latestNotes ?? $currentUser->notificationsE()->latest()->take(5)->get();
@endphp

<nav class="sticky top-0 z-50 border-b border-gray-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                <picture>
                    <source srcset="{{ asset('images/logo-ekaku.webp') }}" type="image/webp">
                    <img src="{{ asset('images/logo-ekaku.png') }}" alt="e-Kaku" class="h-8 w-auto" loading="eager" width="128" height="32">
                </picture>
            </a>

            <div class="ml-auto sm:hidden" x-data="{ show: false }">
                <button type="button" x-on:click="show = true" class="rounded-md p-2.5 text-gray-400 hover:text-red-500 hover:bg-gray-100 transition-colors" title="Keluar">
                    <i class="fas fa-sign-out-alt text-lg"></i>
                </button>
                <div x-show="show" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" role="dialog" aria-modal="true">
                    <div class="fixed inset-0 bg-black/50 transition-opacity" x-transition x-on:click="show = false"></div>
                    <div class="relative w-full max-w-sm rounded-xl bg-white p-6 shadow-2xl" x-transition x-on:click.stop>
                        <div class="flex items-start gap-4">
                            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-gray-100">
                                <i class="fas fa-sign-out-alt text-red-500"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">Keluar?</h3>
                                <p class="mt-2 text-sm text-gray-600">Anda akan keluar dari akun e-Kaku.</p>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" x-on:click="show = false" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</button>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700">Ya, Keluar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hidden items-center gap-6 sm:flex">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'border-disnaker-500 text-disnaker-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">Dashboard</a>
                <a href="{{ route('admin.pengguna.index') }}" class="inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium {{ request()->routeIs('admin.pengguna.*') ? 'border-disnaker-500 text-disnaker-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">Pengguna</a>
                <a href="{{ route('admin.laporan') }}" class="inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium {{ request()->routeIs('admin.laporan*') ? 'border-disnaker-500 text-disnaker-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">Laporan</a>
                <a href="{{ route('admin.verifikasi.index') }}" class="inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium {{ request()->routeIs('admin.verifikasi.*') ? 'border-disnaker-500 text-disnaker-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">Verifikasi</a>
                <a href="{{ route('admin.notification') }}" class="inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium {{ request()->routeIs('admin.notification') ? 'border-disnaker-500 text-disnaker-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}">Notifikasi Manual</a>
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
        <a href="{{ route('admin.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i>
            <span class="bottom-nav-label">Beranda</span>
        </a>
        <a href="{{ route('admin.pengguna.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.pengguna.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            <span class="bottom-nav-label">Pengguna</span>
        </a>
        <a href="{{ route('admin.laporan') }}" class="bottom-nav-item {{ request()->routeIs('admin.laporan*') ? 'active' : '' }}">
            <i class="fas fa-file-alt"></i>
            <span class="bottom-nav-label">Laporan</span>
        </a>
        <a href="{{ route('admin.verifikasi.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.verifikasi.*') ? 'active' : '' }}">
            <i class="fas fa-check-circle"></i>
            <span class="bottom-nav-label">Verifikasi</span>
        </a>
        <a href="{{ route('admin.notification') }}" class="bottom-nav-item {{ request()->routeIs('admin.notification') ? 'active' : '' }}">
            <i class="fas fa-paper-plane"></i>
            <span class="bottom-nav-label">Kirim</span>
        </a>
    </div>
</nav>
