@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="page-wrap" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 150)">

    {{-- SKELETON --}}
    <div x-show="!loaded" class="space-y-6">
        <div class="rounded-xl bg-gray-200 p-6 card-shadow animate-pulse">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <x-skeleton type="title" class="w-1/3" />
                    <x-skeleton type="text-sm" class="mt-2 w-1/2" />
                </div>
                <div class="flex items-center gap-2">
                    <x-skeleton type="badge" />
                    <x-skeleton type="btn" />
                </div>
            </div>
        </div>
        <div class="rounded-xl bg-gray-200 card-shadow animate-pulse">
            <div class="space-y-0">
                @foreach([1,2,3,4] as $i)
                <div class="border-b border-gray-100 px-6 py-4">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex-1 space-y-2">
                            <x-skeleton type="text" class="w-1/3" />
                            <x-skeleton type="text-sm" class="w-2/3" />
                            <x-skeleton type="text-sm" class="w-1/4" />
                        </div>
                        <x-skeleton type="btn" />
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- REAL CONTENT --}}
    <div x-show="loaded" x-cloak class="space-y-6">
    <div class="mb-6 rounded-xl bg-gradient-to-r from-[#005aa7] to-blue-600 p-6 card-shadow">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-white">Notifikasi Saya</h1>
                <p class="mt-1 text-sm text-blue-100">Info validasi biodata, reminder status, dan pembaruan sistem.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                    {{ $unreadCount }} belum dibaca
                </span>
                @if ($unreadCount > 0)
                    <a href="{{ route('notifikasi.index', ['mark_read' => 1]) }}" class="rounded-md border border-white/30 px-3 py-1.5 text-xs font-medium text-white hover:bg-white/20">
                        Tandai semua dibaca
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl bg-white card-shadow">
        @forelse ($notes as $note)
            <div class="border-b border-gray-100 px-6 py-4 {{ $note->status_baca ? 'bg-white' : 'bg-blue-50' }}">
                <div class="flex flex-wrap items-start justify-between gap-2">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $note->judul }}</p>
                        <p class="mt-1 text-sm text-gray-600">{{ $note->pesan }}</p>
                        <p class="mt-1 text-xs text-gray-500">{{ $note->created_at?->format('d M Y H:i') }}</p>
                    </div>
                    @if (! $note->status_baca)
                        <form method="POST" action="{{ route('notifikasi.read', $note) }}" x-data="{ loading: false }" @submit="loading = true">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="rounded-md border border-disnaker-200 px-4 py-2 text-xs font-medium text-disnaker-600 hover:bg-disnaker-50" x-bind:disabled="loading">
                                <span x-show="!loading">Tandai Dibaca</span>
                                <span x-show="loading">...</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="px-6 py-12 text-center">
                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">
                    <i class="fas fa-bell-slash text-gray-400"></i>
                </div>
                <p class="text-sm text-gray-500">Belum ada notifikasi.</p>
            </div>
        @endforelse
    </div>

    @if (method_exists($notes, 'links'))
        <div class="mt-4">{{ $notes->links() }}</div>
    @endif
    </div>
</div>
@endsection
