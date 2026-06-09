<?php

namespace Tests\Feature;

use App\Models\Biodata;
use App\Models\KartuKuning;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KartuKuningExpiryTest extends TestCase
{
    use RefreshDatabase;

    public function test_verify_returns_expired_for_kartu_with_expired_status(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        Biodata::create([
            'user_id' => $user->id,
            'nik' => '3601000000000012',
            'tempat_lahir' => 'Pandeglang',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Alamat',
            'pendidikan' => 'SMA',
            'status_verifikasi' => 'verified',
        ]);
        KartuKuning::create([
            'user_id' => $user->id,
            'nomor_ak1' => 'AK1-20260101-0001',
            'status' => 'expired',
            'tanggal_cetak' => now()->subYears(3),
            'qr_token' => 'valid-token',
        ]);

        $response = $this->get(route('kartu.verify', [
            'nomor_ak1' => 'AK1-20260101-0001',
            'token' => 'valid-token',
        ]));

        $response->assertOk();
        $response->assertSee('Kartu ini sudah kadaluarsa');
    }

    public function test_verify_returns_expired_when_expired_at_is_past(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        Biodata::create([
            'user_id' => $user->id,
            'nik' => '3601000000000012',
            'tempat_lahir' => 'Pandeglang',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Alamat',
            'pendidikan' => 'SMA',
            'status_verifikasi' => 'verified',
        ]);
        $kartu = KartuKuning::create([
            'user_id' => $user->id,
            'nomor_ak1' => 'AK1-20260101-0002',
            'status' => 'printed',
            'tanggal_cetak' => now()->subYears(3),
            'qr_token' => 'valid-token-2',
            'expired_at' => now()->subDay(),
        ]);

        $response = $this->get(route('kartu.verify', [
            'nomor_ak1' => 'AK1-20260101-0002',
            'token' => 'valid-token-2',
        ]));

        $response->assertOk();
        $response->assertSee('Token verifikasi QR sudah kadaluarsa');
        $this->assertEquals('expired', $kartu->fresh()->status);
    }

    public function test_missing_token_returns_invalid(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        Biodata::create([
            'user_id' => $user->id,
            'nik' => '3601000000000012',
            'tempat_lahir' => 'Pandeglang',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Alamat',
            'pendidikan' => 'SMA',
            'status_verifikasi' => 'verified',
        ]);
        KartuKuning::create([
            'user_id' => $user->id,
            'nomor_ak1' => 'AK1-20260101-0003',
            'status' => 'printed',
            'tanggal_cetak' => now(),
            'qr_token' => 'secret-token',
        ]);

        $response = $this->get(route('kartu.verify', [
            'nomor_ak1' => 'AK1-20260101-0003',
        ]));

        $response->assertOk();
        $response->assertSee('Token keamanan tidak valid');
    }
}
