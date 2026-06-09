<?php

namespace Tests\Feature;

use App\Models\Biodata;
use App\Models\StatusPekerjaan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLaporanTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_laporan_page_can_be_rendered(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($admin)->get(route('admin.laporan'));

        $response->assertOk();
    }

    public function test_non_admin_cannot_access_laporan(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('admin.laporan'));

        $response->assertForbidden();
    }

    public function test_guest_cannot_access_laporan(): void
    {
        $response = $this->get(route('admin.laporan'));

        $response->assertRedirect(route('login'));
    }

    public function test_csv_export_returns_csv_file(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
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
        StatusPekerjaan::create([
            'user_id' => $user->id,
            'status_pekerjaan' => 'sudah_bekerja',
            'tanggal_update' => now()->toDateString(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.laporan.export.csv'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString(
            'attachment; filename="laporan-status-pandeglang-',
            $response->headers->get('Content-Disposition'),
        );
    }

    public function test_pdf_export_returns_pdf(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
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

        $response = $this->actingAs($admin)->get(route('admin.laporan.export.pdf'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_laporan_can_be_filtered_by_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user1 = User::factory()->create(['role' => 'user']);
        $user2 = User::factory()->create(['role' => 'user']);
        Biodata::create(['user_id' => $user1->id, 'nik' => '3601000000000013', 'tempat_lahir' => 'Pandeglang', 'tanggal_lahir' => '2000-01-01', 'alamat' => 'Alamat', 'pendidikan' => 'SMA']);
        Biodata::create(['user_id' => $user2->id, 'nik' => '3601000000000014', 'tempat_lahir' => 'Pandeglang', 'tanggal_lahir' => '2000-01-01', 'alamat' => 'Alamat', 'pendidikan' => 'SMA']);
        StatusPekerjaan::create(['user_id' => $user1->id, 'status_pekerjaan' => 'sudah_bekerja', 'tanggal_update' => now()->toDateString()]);
        StatusPekerjaan::create(['user_id' => $user2->id, 'status_pekerjaan' => 'belum_bekerja', 'tanggal_update' => now()->toDateString()]);

        $response = $this->actingAs($admin)->get(route('admin.laporan', ['status' => 'sudah_bekerja']));

        $response->assertOk();
    }
}
