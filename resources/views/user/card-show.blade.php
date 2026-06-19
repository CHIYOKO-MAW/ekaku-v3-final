@extends('layouts.app')

@section('title', 'Kartu Kuning Digital')

@section('content')
<div class="page-wrap">
    <div class="mx-auto max-w-3xl">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kartu Kuning Digital</h1>
                <p class="text-sm text-gray-500">Kartu AK-1 — Dinas Tenaga Kerja Kab. Pandeglang</p>
            </div>
            <a href="{{ route('kartu.download.self') }}" class="btn-primary">
                <i class="fas fa-file-pdf mr-2"></i> Download PDF
            </a>
        </div>

        <div class="flex justify-center">
            <div class="card-preview-wrapper">

<style>
.card-preview-wrapper{width:640px}
.card-preview{width:100%;margin:0 auto;border:1.5px solid #cbd5e1;border-radius:5px;overflow:hidden;background:#fff}
.card-preview .hdr{background:#1e3a5f;color:#fff;padding:7px 10px}
.card-preview .ftr{background:#1e3a5f;color:rgba(255,255,255,0.6);padding:3px 10px;font-size:6px}
.card-preview .body{padding:8px 12px;background:#fff}
.card-preview .nama{font-size:13px;font-weight:bold;text-transform:uppercase;letter-spacing:1px;color:#0f172a}
.card-preview .badge{display:inline-block;background:#dcfce7;color:#16a34a;padding:2px 8px;font-size:7px;font-weight:bold;border-radius:3px}
.card-preview .sec{font-size:7px;font-weight:700;color:#1e3a5f;text-transform:uppercase;letter-spacing:1px;border-bottom:1.5px solid #1e3a5f;padding-bottom:2px;margin-bottom:5px}
.card-preview .fl{font-size:7px;font-weight:600;color:#64748b;white-space:nowrap}
.card-preview .fv{font-size:9px;font-weight:600;color:#0f172a}
.card-preview .tag{display:inline-block;background:#dbeafe;color:#1e40af;padding:1px 5px;font-size:7px;font-weight:bold;margin:1px 1px 1px 0;border-radius:2px}
.card-preview .dline{border:none;border-top:1px solid #e2e8f0;margin:5px 0}
</style>

<div class="card-preview">

    {{-- HEADER --}}
    <div class="hdr">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="34" valign="middle">
                    <picture>
                        <source srcset="{{ asset('images/lambang-pandeglang.webp') }}" type="image/webp">
                        <img src="{{ asset('images/lambang-pandeglang.png') }}" width="28" height="28" alt="" loading="eager">
                    </picture>
                </td>
                <td valign="middle">
                    <div style="font-size:9px;font-weight:bold;text-transform:uppercase;letter-spacing:0.3px">Pemerintah Kab. Pandeglang</div>
                    <div style="font-size:7px;color:rgba(255,255,255,0.65)">Dinas Tenaga Kerja</div>
                    <div style="font-size:8px;font-weight:bold;color:#fde047;margin-top:1px">Kartu Tanda Pencari Kerja (AK/1)</div>
                </td>
                <td width="20"></td>
                <td valign="middle" align="right" style="white-space:nowrap">
                    <div style="font-size:5px;color:rgba(255,255,255,0.45);text-transform:uppercase">No. Registrasi</div>
                    <div style="font-size:8px;font-weight:bold">{{ $user->kartuKuning->nomor_ak1 ?? '-' }}</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- BODY --}}
    <div class="body">

        {{-- FOTO + NAMA --}}
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                {{-- Foto --}}
                <td width="75" valign="top" style="padding-right:10px">
                    @if ($user->profile_photo_path)
                        <div style="width:65px;height:87px;overflow:hidden;border:1.5px solid #1e3a5f;border-radius:2px">
                            <img src="{{ route('profile.photo', $user) }}" style="width:100%;height:100%;object-fit:cover" alt="" loading="eager" width="65" height="87">
                        </div>
                    @else
                        <table width="65" height="87" cellpadding="0" cellspacing="0" style="border:1.5px dashed #cbd5e1;background:#fefce8;border-radius:2px">
                            <tr><td align="center" valign="middle" style="font-size:6px;color:#94a3b8">[Foto 3x4]</td></tr>
                        </table>
                    @endif
                </td>
                {{-- Nama + Badge --}}
                <td valign="middle">
                    <div class="nama">{{ strtoupper($user->name) }}</div>
                    <div style="margin-top:4px"><span class="badge">&#10003; TERVERIFIKASI</span></div>
                </td>
            </tr>
        </table>

        <hr class="dline">

        {{-- DATA DIRI --}}
        <div class="sec">Data Diri</div>

        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="50%" valign="top" style="padding-right:6px">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="55" valign="top"><span class="fl">NIK</span></td>
                            <td><span class="fv">{{ substr($user->biodata->nik ?? '', 0, 6) }}**********</span></td>
                        </tr>
                        <tr>
                            <td valign="top"><span class="fl">Tpt Lahir</span></td>
                            <td><span class="fv">{{ $user->biodata->tempat_lahir ?? '-' }}</span></td>
                        </tr>
                        <tr>
                            <td valign="top"><span class="fl">Tgl Lahir</span></td>
                            <td><span class="fv">{{ $user->biodata->tanggal_lahir ? \Carbon\Carbon::parse($user->biodata->tanggal_lahir)->format('d/m/Y') : '-' }}</span></td>
                        </tr>
                        <tr>
                            <td valign="top"><span class="fl">J Kelamin</span></td>
                            <td><span class="fv">{{ $user->biodata->jenis_kelamin ?? '-' }}</span></td>
                        </tr>
                    </table>
                </td>
                <td width="50%" valign="top">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="55" valign="top"><span class="fl">Pendidikan</span></td>
                            <td><span class="fv">{{ $user->biodata->pendidikan ?? '-' }}{{ $user->biodata->tahun_lulus ? ' ('.$user->biodata->tahun_lulus.')' : '' }}</span></td>
                        </tr>
                        @if ($user->biodata->keahlian ?? null)
                            <tr>
                                <td valign="top"><span class="fl">Keahlian</span></td>
                                <td>@foreach(explode(',', $user->biodata->keahlian) as $skill)<span class="tag">{{ trim($skill) }}</span>@endforeach</td>
                            </tr>
                        @endif
                        @if ($user->biodata->tujuan_lamaran ?? null)
                            <tr>
                                <td valign="top"><span class="fl">Tujuan</span></td>
                                <td><span class="fv">{{ $user->biodata->tujuan_lamaran }}</span></td>
                            </tr>
                        @endif
                    </table>
                </td>
            </tr>
        </table>

        @if ($user->biodata->alamat ?? null)
            <div style="margin-top:4px">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="55" valign="top"><span class="fl">Alamat</span></td>
                        <td><span class="fv">{{ $user->biodata->alamat }}@if($user->biodata->kecamatan), Kec. {{ $user->biodata->kecamatan }}@endif</span></td>
                    </tr>
                </table>
            </div>
        @endif

        <hr class="dline">

        {{-- QR + DATES --}}
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                {{-- QR --}}
                <td width="82" valign="top" align="center">
                    <img src="data:image/svg+xml;base64,{{ $qrBase64 }}" width="72" height="72" alt="QR">
                    <div style="font-size:5px;color:#94a3b8;margin-top:1px;text-align:center">Scan untuk verifikasi</div>
                </td>
                <td width="20"></td>
                {{-- Dates --}}
                <td valign="middle">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="80"><span class="fl">Tanggal Cetak</span></td>
                            <td><span class="fv">{{ optional($user->kartuKuning->tanggal_cetak)->format('d/m/Y') ?? now()->format('d/m/Y') }}</span></td>
                        </tr>
                        <tr>
                            <td><span class="fl">Berlaku Hingga</span></td>
                            <td><span class="fv">{{ $validUntil->format('d/m/Y') }}</span></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

    </div>

    {{-- FOOTER --}}
    <div class="ftr">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td>Dinas Tenaga Kerja Kab. Pandeglang</td>
                <td align="right">e-Kaku V3</td>
            </tr>
        </table>
    </div>

</div>

            </div>
        </div>

        <div class="mt-5 rounded-lg border border-emerald-200 bg-emerald-50 p-4">
            <div class="flex items-start gap-3">
                <i class="fas fa-shield-halved mt-0.5 text-emerald-600"></i>
                <div class="text-sm">
                    <p class="font-semibold text-emerald-800">QR Code Dilindungi HMAC-SHA256</p>
                    <p class="mt-1 text-emerald-700">QR Code mengandung token keamanan unik. Saat discan sistem memverifikasi keaslian secara realtime. Kartu palsu akan terdeteksi sebagai <strong>TIDAK VALID</strong>.</p>
                    @if ($user->kartuKuning && $user->kartuKuning->qr_issued_at)
                        <p class="mt-2 text-xs text-emerald-600">Token terakhir: {{ \Carbon\Carbon::parse($user->kartuKuning->qr_issued_at)->format('d/m/Y H:i') }} WIB &bull; Scan: {{ $user->kartuKuning->scan_count ?? 0 }}x</p>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
