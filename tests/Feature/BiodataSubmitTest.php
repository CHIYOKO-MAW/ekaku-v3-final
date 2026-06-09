<?php

namespace Tests\Feature;

use App\Models\Biodata;
use App\Models\Upload;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BiodataSubmitTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_submit_biodata_for_verification()
    {
        // Create a user and biodata
        $user = User::factory()->create(['role' => 'user']);
        $biodata = Biodata::create([
            'user_id' => $user->id,
            'nik' => '3601000000000012',
            'tempat_lahir' => 'Pandeglang',
            'tanggal_lahir' => '2001-05-10',
            'alamat' => 'Desa Pandeglang, Kec. Pandeglang, Kab. Pandeglang',
            'pendidikan' => 'SMA/SMK',
            'keahlian' => 'Administrasi',
            'status_verifikasi' => 'pending',
        ]);

        // Upload required documents
        foreach (['ktp', 'ijazah', 'foto'] as $jenis) {
            Upload::create([
                'user_id' => $user->id,
                'jenis_dokumen' => $jenis,
                'file_path' => "uploads/{$user->id}/{$jenis}.jpg",
            ]);
        }

        // Act as the user
        $this->actingAs($user);

        // Submit for verification
        $response = $this->post(route('biodata.submit'));

        // Assert redirect and success message
        $response->assertRedirect(route('profile.edit').'#biodata');
        $response->assertSessionHas('success');

        // Assert biodata status is still pending (reset)
        $this->assertDatabaseHas('biodata', [
            'id' => $biodata->id,
            'status_verifikasi' => 'pending',
        ]);
    }
}
