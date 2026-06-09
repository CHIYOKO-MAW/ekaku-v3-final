@extends('layouts.app')

@section('title', 'Dokumen Saya')

@section('content')
@php
    $jenisList = ['ktp' => 'KTP', 'ijazah' => 'Ijazah', 'foto' => 'Foto', 'kk' => 'Kartu Keluarga', 'sertifikat' => 'Sertifikat'];
    $grouped = $uploads->groupBy('jenis_dokumen');
    $icons = ['ktp' => 'fa-id-card', 'ijazah' => 'fa-graduation-cap', 'foto' => 'fa-camera', 'kk' => 'fa-users', 'sertifikat' => 'fa-certificate'];
@endphp
<div class="page-wrap">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Dokumen Saya</h1>
        <p class="mt-1 text-sm text-gray-600">Upload KTP, Ijazah, dan Foto untuk kelengkapan biodata.</p>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
        @foreach ($jenisList as $key => $label)
            @php $docs = $grouped->get($key, collect()); @endphp
            <div class="rounded-xl bg-white p-6 card-shadow" x-data="{ showForm: false }">
                <div class="mb-4 flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-disnaker-100">
                        <i class="fas {{ $icons[$key] }} text-lg text-disnaker-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $label }}</h3>
                        <p class="text-xs text-gray-500">
                            @if ($docs->count())
                                {{ $docs->count() }} file terupload
                            @else
                                Belum upload
                            @endif
                        </p>
                    </div>
                </div>

                <div class="space-y-2">
                    @forelse ($docs as $doc)
                        @php $ext = pathinfo($doc->file_path, PATHINFO_EXTENSION); @endphp
                        <div class="flex items-center gap-3 rounded-lg border border-gray-200 p-2">
                            <a href="{{ route('upload.preview', $doc->id) }}" target="_blank" class="flex h-10 w-10 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg bg-gray-100">
                                @if (in_array($ext, ['jpg', 'jpeg', 'png']))
                                    <img src="{{ route('upload.preview', $doc->id) }}" alt="preview" class="h-full w-full object-cover">
                                @else
                                    <i class="fas fa-file-pdf text-red-500"></i>
                                @endif
                            </a>
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('upload.preview', $doc->id) }}" target="_blank" class="block truncate text-sm font-medium text-gray-900 hover:text-disnaker-600">
                                    {{ basename($doc->file_path) }}
                                </a>
                                <p class="text-xs text-gray-500">{{ $doc->created_at->format('d M Y') }}</p>
                            </div>
                            <div x-data="{ confirm: false }">
                                <button @click.prevent="confirm = true" class="rounded-lg p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-500">
                                    <i class="fas fa-trash-alt text-sm"></i>
                                </button>
                                <div x-cloak x-show="confirm" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.away="confirm = false">
                                    <div class="rounded-xl bg-white p-6 shadow-xl" @click.stop>
                                        <p class="text-sm text-gray-700">Hapus dokumen <span class="font-semibold">{{ basename($doc->file_path) }}</span>?</p>
                                        <div class="mt-4 flex gap-2">
                                            <form action="{{ route('upload.destroy', $doc->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-danger text-sm">Hapus</button>
                                            </form>
                                            <button @click="confirm = false" class="btn-secondary text-sm">Batal</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center rounded-lg border-2 border-dashed border-gray-200 py-8">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-300"></i>
                            <p class="mt-2 text-sm text-gray-400">Belum ada {{ strtolower($label) }}</p>
                        </div>
                    @endforelse
                </div>

                @if ($key === 'sertifikat')
                    <p class="mt-3 text-xs italic text-gray-400">*Jika Sudah Punya</p>
                @endif

                <button @click="showForm = !showForm" class="btn-secondary mt-4 w-full">
                    <i class="fas mr-2" :class="showForm ? 'fa-times' : 'fa-upload'"></i>
                    <span x-text="showForm ? 'Batal' : 'Upload {{ $label }}'"></span>
                </button>

                <form x-cloak x-show="showForm" action="{{ route('upload.store') }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-3 rounded-lg border border-disnaker-200 bg-disnaker-50 p-4">
                    @csrf
                    <input type="hidden" name="jenis_dokumen" value="{{ $key }}">
                    <div>
                        <x-input-label for="file_{{ $key }}" value="File (jpg/png/pdf, max 5MB)" />
                        <input id="file_{{ $key }}" type="file" name="file" class="form-input" required>
                        <x-input-error :messages="$errors->get('file')" />
                    </div>
                    <button type="submit" class="btn-primary w-full">
                        <i class="fas fa-upload mr-2"></i>Upload
                    </button>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection