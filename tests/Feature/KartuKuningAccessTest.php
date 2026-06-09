<?php

namespace Tests\Feature;

use App\Models\Biodata;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KartuKuningAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_unverified_user_cannot_preview_kartu(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        Biodata::create([
            'user_id' => $user->id,
            'nik' => '3601000000000012',
            'tempat_lahir' => 'Pandeglang',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Alamat',
            'pendidikan' => 'SMA',
            'keahlian' => 'Admin',
            'status_verifikasi' => 'pending',
        ]);

        $response = $this->actingAs($user)->get(route('kartu.show'));

        $response->assertRedirect();
    }

    public function test_unverified_user_cannot_download_kartu(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        Biodata::create([
            'user_id' => $user->id,
            'nik' => '3601000000000012',
            'tempat_lahir' => 'Pandeglang',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Alamat',
            'pendidikan' => 'SMA',
            'keahlian' => 'Admin',
            'status_verifikasi' => 'pending',
        ]);

        $response = $this->actingAs($user)->get(route('kartu.download.self'));

        $response->assertRedirect();
    }

    public function test_verified_user_can_download_kartu_as_pdf(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        Biodata::create([
            'user_id' => $user->id,
            'nik' => '3601000000000012',
            'tempat_lahir' => 'Pandeglang',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Alamat',
            'pendidikan' => 'SMA',
            'keahlian' => 'Admin',
            'status_verifikasi' => 'verified',
        ]);
        $user->kartuKuning()->create([
            'nomor_ak1' => 'AK1-20260101-0001',
            'status' => 'printed',
            'tanggal_cetak' => now(),
        ]);
        $user->generateQrCode();

        $response = $this->actingAs($user)->get(route('kartu.download.self'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_admin_can_download_any_users_kartu(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);
        Biodata::create([
            'user_id' => $user->id,
            'nik' => '3601000000000012',
            'tempat_lahir' => 'Pandeglang',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Alamat',
            'pendidikan' => 'SMA',
            'keahlian' => 'Admin',
            'status_verifikasi' => 'verified',
        ]);
        $kartu = $user->kartuKuning()->create([
            'nomor_ak1' => 'AK1-20260101-0001',
            'status' => 'printed',
            'tanggal_cetak' => now(),
        ]);
        $user->generateQrCode();

        $response = $this->actingAs($admin)->get(route('kartu.download', $kartu->user_id));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_other_user_cannot_download_someone_elses_kartu(): void
    {
        $owner = User::factory()->create(['role' => 'user']);
        $other = User::factory()->create(['role' => 'user']);
        Biodata::create([
            'user_id' => $owner->id,
            'nik' => '3601000000000012',
            'tempat_lahir' => 'Pandeglang',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Alamat',
            'pendidikan' => 'SMA',
            'keahlian' => 'Admin',
            'status_verifikasi' => 'verified',
        ]);
        $kartu = $owner->kartuKuning()->create([
            'nomor_ak1' => 'AK1-20260101-0001',
            'status' => 'printed',
            'tanggal_cetak' => now(),
        ]);
        $owner->generateQrCode();

        $response = $this->actingAs($other)->get(route('kartu.download', $kartu->user_id));

        $response->assertForbidden();
    }
}
