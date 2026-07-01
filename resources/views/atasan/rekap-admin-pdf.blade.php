<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Kinerja Admin - e-Kaku</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        h2 { font-size: 14px; margin-bottom: 16px; color: #666; }
        .header { margin-bottom: 24px; border-bottom: 2px solid #005aa7; padding-bottom: 12px; }
        .meta { font-size: 11px; color: #999; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th { background: #f3f4f6; text-align: left; padding: 8px; border: 1px solid #e5e7eb; font-weight: 600; font-size: 11px; }
        td { padding: 8px; border: 1px solid #e5e7eb; font-size: 11px; }
        tr:nth-child(even) { background: #f9fafb; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 9999px; font-size: 10px; font-weight: 600; }
        .badge-green { background: #d1fae5; color: #065f46; }
        .badge-red { background: #fee2e2; color: #991b1b; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .footer { margin-top: 24px; font-size: 10px; color: #999; border-top: 1px solid #e5e7eb; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rekap Kinerja Admin</h1>
        <h2>Dinas Ketenagakerjaan Kabupaten Pandeglang</h2>
        @if($startDate || $endDate)
        <p class="meta">Periode: {{ $startDate ?? 'Awal' }} s/d {{ $endDate ?? 'Sekarang' }}</p>
        @endif
        <p class="meta">Dicetak: {{ $generatedAt->format('d M Y, H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Admin</th>
                <th>Email</th>
                <th>Total Verifikasi</th>
                <th>Verified</th>
                <th>Rejected</th>
                <th>Approval Rate</th>
            </tr>
        </thead>
        <tbody>
            @forelse($adminData as $index => $admin)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $admin['name'] }}</strong></td>
                <td>{{ $admin['email'] }}</td>
                <td>{{ $admin['total'] }}</td>
                <td><span class="badge badge-green">{{ $admin['verified'] }}</span></td>
                <td><span class="badge badge-red">{{ $admin['rejected'] }}</span></td>
                <td>
                    <span class="badge {{ $admin['approval_rate'] >= 80 ? 'badge-green' : ($admin['approval_rate'] >= 50 ? 'badge-yellow' : 'badge-red') }}">
                        {{ $admin['approval_rate'] }}%
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 24px;">Belum ada data admin.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dihasilkan secara otomatis oleh sistem e-Kaku v3.</p>
    </div>
</body>
</html>
