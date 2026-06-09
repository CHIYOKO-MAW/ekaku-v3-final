<?php

namespace Tests\Feature;

use App\Models\Biodata;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BiodataEdgeCaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_duplicate_nik_is_rejected(): void
    {
        $user1 = User::factory()->create(['role' => 'user']);
        $user2 = User::factory()->create(['role' => 'user']);

        Biodata::create([
            'user_id' => $user1->id,
            'nik' => '3601000000000012',
            'tempat_lahir' => 'Pandeglang',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Alamat 1',
            'pendidikan' => 'SMA',
            'keahlian' => 'Admin',
            'status_verifikasi' => 'pending',
        ]);

        $response = $this->actingAs($user2)->post(route('biodata.store'), [
            'nik' => '3601000000000012',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Alamat 2',
            'pendidikan' => 'S1',
            'keahlian' => 'Marketing',
        ]);

        $response->assertSessionHasErrors('nik');
    }

    public function test_rejected_biodata_resets_to_pending_on_update(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $biodata = Biodata::create([
            'user_id' => $user->id,
            'nik' => '3601000000000033',
            'tempat_lahir' => 'Pandeglang',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Alamat lama',
            'pendidikan' => 'SMA',
            'keahlian' => 'Admin',
            'status_verifikasi' => 'rejected',
        ]);

        $this->actingAs($user)->put(route('biodata.update'), [
            'nik' => '3601000000000033',
            'tempat_lahir' => 'Serang',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Alamat baru',
            'pendidikan' => 'S1',
            'keahlian' => 'Marketing',
        ]);

        $this->assertDatabaseHas('biodata', [
            'id' => $biodata->id,
            'status_verifikasi' => 'pending',
        ]);
    }

    public function test_soft_deleted_biodata_can_be_restored_on_create(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $biodata = Biodata::create([
            'user_id' => $user->id,
            'nik' => '3601000000000044',
            'tempat_lahir' => 'Pandeglang',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Alamat',
            'pendidikan' => 'SMA',
            'keahlian' => 'Admin',
            'status_verifikasi' => 'pending',
        ]);
        $biodata->delete();

        $this->assertNotNull(Biodata::withTrashed()->find($biodata->id));

        $this->actingAs($user)->post(route('biodata.store'), [
            'nik' => '3601000000000044',
            'tempat_lahir' => 'Pandeglang',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Alamat',
            'pendidikan' => 'SMA',
            'keahlian' => 'Admin',
        ]);

        $restored = Biodata::find($biodata->id);
        $this->assertNotNull($restored);
        $this->assertEquals('pending', $restored->status_verifikasi);
    }

    public function test_guest_cannot_create_biodata(): void
    {
        $response = $this->post(route('biodata.store'), [
            'nik' => '3601000000000055',
            'tempat_lahir' => 'Pandeglang',
            'tanggal_lahir' => '2000-01-01',
            'alamat' => 'Alamat',
            'pendidikan' => 'SMA',
            'keahlian' => 'Admin',
        ]);

        $response->assertRedirect(route('login'));
    }
}
