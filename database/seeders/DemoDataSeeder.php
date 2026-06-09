<?php

namespace Database\Seeders;

use App\Models\Biodata;
use App\Models\KartuKuning;
use App\Models\NotificationE;
use App\Models\StatusPekerjaan;
use App\Models\Upload;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    private array $pendidikanPool = [
        'SD', 'SMP', 'SMA/SMK', 'D3', 'S1', 'S2',
    ];

    private array $pendidikanWeight = [5, 10, 45, 20, 18, 2];

    private array $agamaWeight = [998, 1, 1];

    private array $skillPool = [
        'Petani', 'Nelayan', 'Buruh Tani', 'Peternak',
        'Pemandu Wisata', 'Staff Hotel', 'Chef', 'Tour Guide',
        'Pedagang', 'Kasir', 'Sales', 'Admin Perkantoran',
        'Driver', 'Ojek', 'Security', 'Cleaning Service',
        'Tukang Bangunan', 'Mandor', 'Tukang Kayu',
        'Operator Produksi', 'Pengemasan',
        'Staff Desa', 'Operator Komputer', 'Penyuluh Pertanian',
        'Pembudidaya Ikan', 'Pengolah Hasil Laut',
    ];

    private array $companyPool = [
        'PT. Banten Indah Persada', 'CV. Cahaya Mandiri',
        'UD. Tani Subur', 'PT. Samudra Jaya Abadi',
        'CV. Karang Tanjung', 'Hotel Tanjung Lesung',
        'PT. Bumi Lestari', 'UD. Berkah Jaya',
        'CV. Pandeglang Makmur', 'PT. Citra Mandiri Sejahtera',
        'Toko Sembako Berkah', 'Warung Makan Sadulur',
        'Bengkel Las Mandiri', 'CV. Multi Karya',
        'PT. Agro Banten Sejahtera',
    ];

    private array $institusiPool = [
        'SMAN 1 Pandeglang', 'SMKN 1 Pandeglang',
        'SMAN 1 Labuan', 'SMKN 1 Labuan',
        'SMPN 1 Pandeglang', 'SMPN 2 Pandeglang',
        'Universitas Banten Jaya', 'Universitas Mathla\'ul Anwar',
        'Politeknik Negeri Banten', 'STIE Banten',
        'SMK Pertanian Pandeglang', 'SMA PGRI Pandeglang',
    ];

    private array $jurusanPool = [
        'TKJ', 'Akuntansi', 'Manajemen', 'Teknik Mesin',
        'Administrasi Perkantoran', 'Pemasaran', 'Agribisnis',
        'Perikanan', 'Pariwisata', 'Multimedia',
        'Manajemen Informatika', 'Ekonomi Syariah',
    ];

    public function run(): void
    {
        $admin = User::where('email', 'admin@ekaku.test')->first();
        if (! $admin) {
            $admin = User::create([
                'name' => 'Demo Admin',
                'email' => 'admin@ekaku.test',
                'role' => 'admin',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]);
        }

        $this->updateDefaultUsers($admin);

        $districts = $this->loadPandeglangDistricts();
        $faker = fake('id_ID');

        $existingCount = User::where('role', 'user')
            ->where('email', 'like', 'demo.user.%@ekaku.demo')
            ->count();
        $target = 300;
        $toCreate = max(0, $target - $existingCount);
        $nextNumber = $existingCount + 1;

        for ($i = 0; $i < $toCreate; $i++, $nextNumber++) {
            $email = sprintf('demo.user.%03d@ekaku.demo', $nextNumber);
            if (User::where('email', $email)->exists()) {
                continue;
            }

            $district = $districts[array_rand($districts)];
            $village = $district['villages'][array_rand($district['villages'])] ?? ['name' => 'Tidak diketahui'];
            $verificationStatus = $this->weightedRandom([
                'verified' => 58,
                'pending' => 28,
                'rejected' => 14,
            ]);
            $pendidikan = $this->weightedPick($this->pendidikanPool, $this->pendidikanWeight);
            $agama = $this->weightedPick(['Islam', 'Kristen', 'Buddha'], $this->agamaWeight);
            $tanggalLahir = $faker->dateTimeBetween('-45 years', '-18 years');
            $tanggalVerifikasi = $verificationStatus === 'pending'
                ? null
                : $faker->dateTimeBetween('2025-01-01', 'now');
            $tanggalCetak = $verificationStatus === 'verified'
                ? (clone $tanggalVerifikasi)->modify('+'.random_int(1, 14).' days')
                : null;

            $user = User::create([
                'name' => $faker->name(),
                'email' => $email,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'user',
            ]);

            $tinggi = random_int(155, 175);
            $berat = random_int(50, 80);

            Biodata::create([
                'user_id' => $user->id,
                'nik' => $this->generateNik(),
                'tempat_lahir' => $faker->city(),
                'tanggal_lahir' => $tanggalLahir->format('Y-m-d'),
                'alamat' => sprintf(
                    'Kp. %s, Desa/Kel. %s, Kec. %s, Kab. Pandeglang, Banten',
                    $faker->streetName(),
                    $village['name'],
                    $district['name']
                ),
                'jenis_kelamin' => random_int(0, 1) === 1 ? 'laki_laki' : 'perempuan',
                'agama' => $agama,
                'rt_rw' => sprintf('%03d/%03d', random_int(1, 20), random_int(1, 20)),
                'kode_pos' => (string) random_int(42210, 42299),
                'kecamatan' => $district['name'],
                'desa_kelurahan' => $village['name'],
                'status_perkawinan' => $this->weightedRandom([
                    'belum_menikah' => 55,
                    'menikah' => 40,
                    'cerai_hidup' => 3,
                    'cerai_mati' => 2,
                ]),
                'tinggi_badan' => $tinggi,
                'berat_badan' => $berat,
                'disabilitas' => random_int(1, 100) <= 3,
                'pendidikan' => $pendidikan,
                'tahun_lulus' => (string) random_int(max(2000, (int) $tanggalLahir->format('Y') + 15), 2025),
                'institusi_pendidikan' => $this->institusiPool[array_rand($this->institusiPool)],
                'jurusan' => in_array($pendidikan, ['SD', 'SMP']) ? null : $this->jurusanPool[array_rand($this->jurusanPool)],
                'keahlian' => $this->skillPool[array_rand($this->skillPool)],
                'pengalaman' => random_int(1, 100) <= 50 ? $faker->sentence(10) : null,
                'tujuan_lamaran' => random_int(1, 100) <= 60 ? $this->companyPool[array_rand($this->companyPool)] : null,
                'status_verifikasi' => $verificationStatus,
                'tanggal_verifikasi' => $tanggalVerifikasi?->format('Y-m-d'),
                'verifikator_id' => $verificationStatus === 'pending' ? null : $admin->id,
            ]);

            if (random_int(1, 100) <= 90) {
                $statusPekerjaan = $this->weightedRandom([
                    'belum_bekerja' => 58,
                    'sudah_bekerja' => 42,
                ]);
                StatusPekerjaan::create([
                    'user_id' => $user->id,
                    'status_pekerjaan' => $statusPekerjaan,
                    'nama_perusahaan' => $statusPekerjaan === 'sudah_bekerja'
                        ? $this->companyPool[array_rand($this->companyPool)]
                        : null,
                    'tanggal_update' => $faker->dateTimeBetween('2025-01-01', 'now')->format('Y-m-d'),
                ]);
            }

            if ($verificationStatus === 'verified') {
                $nomorAk1 = 'AK1-'.$tanggalCetak->format('Ymd').'-'.str_pad((string) $user->id, 4, '0', STR_PAD_LEFT);
                $isExpired = random_int(1, 100) <= 10;
                $qrIssuedAt = clone $tanggalCetak;

                KartuKuning::create([
                    'user_id' => $user->id,
                    'nomor_ak1' => $nomorAk1,
                    'tanggal_cetak' => $tanggalCetak->format('Y-m-d'),
                    'status' => $isExpired ? 'expired' : 'printed',
                    'qr_token' => hash_hmac('sha256', $user->id.':'.$nomorAk1.':'.$qrIssuedAt->getTimestamp(), config('app.key')),
                    'qr_issued_at' => $qrIssuedAt,
                    'expired_at' => (clone $qrIssuedAt)->modify('+2 years'),
                    'scan_count' => random_int(0, 15),
                ]);

                if (random_int(1, 100) <= 60) {
                    foreach (['ktp', 'ijazah', 'foto'] as $jenis) {
                        Upload::create([
                            'user_id' => $user->id,
                            'jenis_dokumen' => $jenis,
                            'file_path' => 'uploads/demo/'.$user->id.'/'.$jenis.'.jpg',
                        ]);
                    }
                }
            }

            if (random_int(1, 100) <= 45) {
                $tipe = $this->weightedRandom(['status_update' => 85, 'kartu_expired' => 15]);
                NotificationE::create([
                    'user_id' => $user->id,
                    'judul' => $tipe === 'kartu_expired'
                        ? 'Kartu Kuning Expired'
                        : 'Reminder Update Status Pekerjaan',
                    'pesan' => $tipe === 'kartu_expired'
                        ? 'Kartu AK1 Anda telah expired. Silakan ajukan perpanjangan ke Disnaker Pandeglang.'
                        : 'Silakan perbarui status pekerjaan Anda untuk menjaga data Disnaker tetap akurat.',
                    'status_baca' => random_int(0, 1) === 1,
                    'tipe' => $tipe,
                ]);
            }
        }
    }

    private function updateDefaultUsers(User $admin): void
    {
        $demoUser = User::where('email', 'user@ekaku.test')->first();
        if ($demoUser) {
            Biodata::updateOrCreate(
                ['user_id' => $demoUser->id],
                [
                    'nik' => '3601000000000001',
                    'tempat_lahir' => 'Pandeglang',
                    'tanggal_lahir' => '2000-01-01',
                    'alamat' => 'Jl. Ahmad Yani No. 12, Kel. Pandeglang, Kec. Pandeglang, Kab. Pandeglang, Banten',
                    'jenis_kelamin' => 'laki_laki',
                    'agama' => 'Islam',
                    'rt_rw' => '001/003',
                    'kode_pos' => '42211',
                    'kecamatan' => 'Pandeglang',
                    'desa_kelurahan' => 'Pandeglang',
                    'status_perkawinan' => 'belum_menikah',
                    'tinggi_badan' => 170,
                    'berat_badan' => 65,
                    'disabilitas' => false,
                    'pendidikan' => 'SMA/SMK',
                    'tahun_lulus' => '2018',
                    'institusi_pendidikan' => 'SMAN 1 Pandeglang',
                    'jurusan' => 'IPA',
                    'keahlian' => 'Administrasi Perkantoran',
                    'pengalaman' => 'Pernah magang di Kantor Kecamatan Pandeglang selama 3 bulan.',
                    'tujuan_lamaran' => 'PT. Banten Indah Persada',
                    'status_verifikasi' => 'verified',
                    'tanggal_verifikasi' => '2025-01-15',
                    'verifikator_id' => $admin->id,
                ]
            );

            $nomorAk1 = 'AK1-20250120-0001';
            KartuKuning::updateOrCreate(
                ['user_id' => $demoUser->id],
                [
                    'nomor_ak1' => $nomorAk1,
                    'tanggal_cetak' => '2025-01-20',
                    'status' => 'printed',
                    'qr_token' => hash_hmac('sha256', $demoUser->id.':'.$nomorAk1.':'.strtotime('2025-01-20'), config('app.key')),
                    'qr_issued_at' => '2025-01-20',
                    'expired_at' => '2027-01-20',
                    'scan_count' => 3,
                ]
            );

            foreach (['ktp', 'ijazah', 'foto'] as $jenis) {
                Upload::updateOrCreate(
                    ['user_id' => $demoUser->id, 'jenis_dokumen' => $jenis],
                    ['file_path' => 'uploads/demo/user/'.$jenis.'.jpg']
                );
            }
        }

        $atasan = User::where('email', 'atasan@ekaku.test')->first();
        if ($atasan) {
            Biodata::updateOrCreate(
                ['user_id' => $atasan->id],
                [
                    'nik' => '3601999999999999',
                    'tempat_lahir' => 'Pandeglang',
                    'tanggal_lahir' => '1980-06-15',
                    'alamat' => 'Jl. Merdeka No. 45, Kel. Pandeglang, Kec. Pandeglang, Kab. Pandeglang, Banten',
                    'jenis_kelamin' => 'laki_laki',
                    'agama' => 'Islam',
                    'rt_rw' => '002/005',
                    'kode_pos' => '42211',
                    'kecamatan' => 'Pandeglang',
                    'desa_kelurahan' => 'Pandeglang',
                    'status_perkawinan' => 'menikah',
                    'tinggi_badan' => 172,
                    'berat_badan' => 70,
                    'disabilitas' => false,
                    'pendidikan' => 'S1',
                    'tahun_lulus' => '2003',
                    'institusi_pendidikan' => 'Universitas Mathla\'ul Anwar',
                    'jurusan' => 'Manajemen',
                    'keahlian' => 'Kepemimpinan',
                    'pengalaman' => 'Bekerja di Disnaker Pandeglang sejak 2010.',
                    'tujuan_lamaran' => null,
                    'status_verifikasi' => 'verified',
                    'tanggal_verifikasi' => '2025-01-10',
                    'verifikator_id' => $admin->id,
                ]
            );
        }
    }

    private function weightedRandom(array $weightedItems): string
    {
        $total = array_sum($weightedItems);
        $rand = random_int(1, $total);
        $cumulative = 0;
        foreach ($weightedItems as $item => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) {
                return $item;
            }
        }

        return array_key_first($weightedItems);
    }

    private function weightedPick(array $items, array $weights): string
    {
        $total = array_sum($weights);
        $rand = random_int(1, $total);
        $cumulative = 0;
        foreach ($items as $i => $item) {
            $cumulative += $weights[$i];
            if ($rand <= $cumulative) {
                return $item;
            }
        }

        return $items[0];
    }

    private function loadPandeglangDistricts(): array
    {
        $path = database_path('data/pandeglang_wilayah.json');
        if (is_file($path)) {
            $payload = json_decode((string) file_get_contents($path), true);
            if (is_array($payload) && isset($payload['districts']) && is_array($payload['districts']) && count($payload['districts']) > 0) {
                return $payload['districts'];
            }
        }

        return [
            ['code' => '36.01.21', 'name' => 'Pandeglang', 'villages' => [['name' => 'Pandeglang']]],
            ['code' => '36.01.34', 'name' => 'Majasari', 'villages' => [['name' => 'Majasari']]],
            ['code' => '36.01.12', 'name' => 'Labuan', 'villages' => [['name' => 'Labuan']]],
            ['code' => '36.01.13', 'name' => 'Menes', 'villages' => [['name' => 'Menes']]],
            ['code' => '36.01.06', 'name' => 'Panimbang', 'villages' => [['name' => 'Panimbang']]],
        ];
    }

    private function generateNik(): string
    {
        do {
            $nik = (string) random_int(3601000000000000, 3601999999999999);
        } while (Biodata::where('nik', $nik)->exists());

        return $nik;
    }
}
