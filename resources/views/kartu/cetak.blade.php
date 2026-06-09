<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kartu Kuning AK/1 - {{ $user->name }}</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:DejaVu Sans,sans-serif;color:#0f172a;margin:0;padding:0}
.page{width:100%;padding:8mm 0}
.card{width:170mm;margin:0 auto;border:1.5px solid #cbd5e1;border-radius:5px;overflow:hidden}
.hdr{background:#1e3a5f;color:#fff;padding:7px 10px}
.ftr{background:#1e3a5f;color:rgba(255,255,255,0.6);padding:3px 10px;font-size:6px}
.body{padding:8px 12px;background:#fff}

.nama{font-size:13px;font-weight:bold;text-transform:uppercase;letter-spacing:1px;color:#0f172a}
.badge{display:inline-block;background:#dcfce7;color:#16a34a;padding:1px 8px;font-size:6px;font-weight:bold;border-radius:3px}
.sec{font-size:7px;font-weight:700;color:#1e3a5f;text-transform:uppercase;letter-spacing:1px;border-bottom:1.5px solid #1e3a5f;padding-bottom:2px;margin-bottom:5px}

.fl{font-size:7px;font-weight:600;color:#64748b;white-space:nowrap}
.fv{font-size:9px;font-weight:600;color:#0f172a}
.tag{display:inline-block;background:#dbeafe;color:#1e40af;padding:1px 5px;font-size:7px;font-weight:bold;margin:1px 1px 1px 0;border-radius:2px}

.dline{border:none;border-top:1px solid #e2e8f0;margin:5px 0}
</style>
</head>
<body>
<div class="page">

<div class="card">

    {{-- HEADER --}}
    <div class="hdr">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="34" valign="middle">
                    <img src="{{ public_path('images/lambang-pandeglang.png') }}" width="28" height="28" alt="">
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
                    @php
                        $fotoPath = $user->profile_photo_path
                            ? storage_path('app/public/'.ltrim($user->profile_photo_path, '/'))
                            : null;
                    @endphp
                    @if ($fotoPath && file_exists($fotoPath))
                        <img src="{{ $fotoPath }}" width="65" height="87" alt="" style="border:1.5px solid #1e3a5f;display:block;border-radius:2px">
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
                    <div style="font-size:5px;color:#94a3b8;margin-top:1px">Scan untuk verifikasi</div>
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
</body>
</html>
