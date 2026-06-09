<?php

namespace Tests\Feature;

use App\Models\StatusPekerjaan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatusPekerjaanTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_status_page(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('status.index'));

        $response->assertOk();
    }

    public function test_user_can_store_new_status(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->from(route('status.index'))->post(route('status.store'), [
            'status_pekerjaan' => 'belum_bekerja',
        ]);

        $response->assertRedirect(route('status.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('status_pekerjaan', [
            'user_id' => $user->id,
            'status_pekerjaan' => 'belum_bekerja',
        ]);
    }

    public function test_user_can_update_existing_status(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        StatusPekerjaan::create([
            'user_id' => $user->id,
            'status_pekerjaan' => 'belum_bekerja',
        ]);

        $response = $this->actingAs($user)->from(route('status.index'))->post(route('status.store'), [
            'status_pekerjaan' => 'sudah_bekerja',
            'nama_perusahaan' => 'PT Maju',
        ]);

        $response->assertRedirect(route('status.index'));
        $this->assertDatabaseHas('status_pekerjaan', [
            'user_id' => $user->id,
            'status_pekerjaan' => 'sudah_bekerja',
            'nama_perusahaan' => 'PT Maju',
        ]);
    }

    public function test_guest_cannot_access_status(): void
    {
        $response = $this->get(route('status.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_nama_perusahaan_is_null_when_belum_bekerja(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->post(route('status.store'), [
            'status_pekerjaan' => 'belum_bekerja',
            'nama_perusahaan' => 'PT Maju',
        ]);

        $this->assertDatabaseHas('status_pekerjaan', [
            'user_id' => $user->id,
            'status_pekerjaan' => 'belum_bekerja',
            'nama_perusahaan' => null,
        ]);
    }
}
