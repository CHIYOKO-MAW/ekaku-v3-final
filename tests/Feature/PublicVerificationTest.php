<?php

namespace Tests\Feature;

use App\Models\Biodata;
use App\Models\KartuKuning;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicVerificationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private KartuKuning $kartu;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'user']);
        Biodata::create([
            'user_id' => $this->user->id,
            'nik' => '3601000000000012',
            'tempat_lahir' => 'Pandeglang',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Alamat',
            'pendidikan' => 'SMA',
            'keahlian' => 'Admin',
            'status_verifikasi' => 'verified',
        ]);
        $this->kartu = $this->user->kartuKuning()->create([
            'nomor_ak1' => 'AK1-20260101-0001',
            'status' => 'printed',
            'tanggal_cetak' => now(),
        ]);
        $this->user->generateQrCode();
        $this->kartu->refresh();
    }

    public function test_verification_succeeds_with_valid_token(): void
    {
        $response = $this->get(route('kartu.verify', [
            'nomor_ak1' => $this->kartu->nomor_ak1,
            'token' => $this->kartu->qr_token,
        ]));

        $response->assertOk();
        $response->assertSee($this->user->name);
    }

    public function test_verification_fails_with_invalid_token(): void
    {
        $response = $this->get(route('kartu.verify', [
            'nomor_ak1' => $this->kartu->nomor_ak1,
            'token' => 'fake-token',
        ]));

        $response->assertOk();
        $response->assertSee('Token keamanan tidak valid');
    }

    public function test_verification_fails_with_nonexistent_nomor_ak1(): void
    {
        $response = $this->get(route('kartu.verify', [
            'nomor_ak1' => 'AK1-99999999-9999',
            'token' => 'some-token',
        ]));

        $response->assertOk();
        $response->assertSee('tidak ditemukan dalam sistem');
    }

    public function test_verification_fails_with_missing_token(): void
    {
        $response = $this->get(route('kartu.verify', [
            'nomor_ak1' => $this->kartu->nomor_ak1,
        ]));

        $response->assertOk();
        $response->assertSee('Token keamanan tidak valid');
    }

    public function test_verification_fails_for_expired_kartu(): void
    {
        $this->kartu->update(['status' => 'expired']);

        $response = $this->get(route('kartu.verify', [
            'nomor_ak1' => $this->kartu->nomor_ak1,
            'token' => $this->kartu->qr_token,
        ]));

        $response->assertOk();
        $response->assertSee('kadaluarsa');
    }
}
