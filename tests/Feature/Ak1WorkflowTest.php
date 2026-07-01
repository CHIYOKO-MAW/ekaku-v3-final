<?php

namespace Tests\Feature;

use App\Models\Biodata;
use App\Models\KartuKuning;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class Ak1WorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_ak1_workflow()
    {
        // 1. Register a new user
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/dashboard');

        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('user', $user->role, 'The user role is not user. It is: '.$user->role);

        // Automatically logged in after registration
        $this->actingAs($user);

        // 2. Submit the biodata form
        $biodataData = [
            'nik' => '3601000000000012',
            'tempat_lahir' => 'Pandeglang',
            'tanggal_lahir' => '2001-05-10',
            'alamat' => 'Desa Pandeglang, Kec. Pandeglang, Kab. Pandeglang',
            'pendidikan' => 'SMA/SMK',
            'keahlian' => 'Administrasi',
        ];

        $response = $this->post(route('biodata.store'), $biodataData);
        $response->assertRedirect(route('profile.edit').'#biodata');
        $response->assertSessionHas('success');

        // Ensure biodata exists and is pending
        $user->refresh(); // Refresh user to load relationships
        $this->assertNotNull($user->biodata, 'Biodata was not created after store. Biodata count: '.Biodata::count());
        $this->assertEquals('pending', $user->biodata->status_verifikasi, 'Biodata status is not pending after store. Current status: '.$user->biodata->status_verifikasi);

        // 3. Upload required documents (KTP, Ijazah, Foto)
        foreach (['ktp', 'ijazah', 'foto'] as $jenis) {
            $file = UploadedFile::fake()->image($jenis.'.jpg');

            $response = $this->post(route('upload.store'), [
                'file' => $file,
                'jenis_dokumen' => $jenis,
            ]);
            $response->assertRedirect(route('profile.edit').'#berkas');
            $response->assertSessionHas('success');
        }

        // Ensure uploads exist
        $user->refresh();
        $this->assertCount(3, $user->uploads()->get(), 'Not all 3 uploads were created after store. Upload count: '.$user->uploads()->count());

        // 4. Submit for verification
        // Debug: Check biodata state before submission
        $user->refresh();
        $this->assertNotNull($user->biodata, 'Biodata missing before verification submission. User ID: '.$user->id.' Biodata count: '.Biodata::where('user_id', $user->id)->count());
        $this->assertEquals('pending', $user->biodata->status_verifikasi, 'Biodata status is not pending before verification. Current status: '.$user->biodata->status_verifikasi);

        $response = $this->post(route('biodata.submit'));
        $response->assertRedirect(route('profile.edit').'#biodata');

        $response->assertSessionMissing('error');
        $response->assertSessionHas('success');

        // 5. Log in as admin (actingAs will override the current user)
        $admin = User::firstOrCreate(
            ['email' => 'admin@ekaku.test'],
            [
                'role' => 'admin',
                'name' => 'Admin User',
                'password' => bcrypt('password'),
            ]
        );
        $this->assertNotNull($admin);
        $this->actingAs($admin);

        // 6. Go to the verification page and verify the user
        $response = $this->get(route('admin.verifikasi.index'));
        $response->assertOk('Verification page did not return 200.');
        $response->assertSee($user->name, 'User name not found on verification page.');

        $biodata = $user->fresh()->biodata;
        $this->assertNotNull($biodata, 'Biodata not found when checking verification page.');
        $this->assertEquals('pending', $biodata->status_verifikasi, 'Biodata status is not pending before verification. Current status: '.$biodata->status_verifikasi);

        // 7. Update the biodata status to verified
        $response = $this->post(route('admin.verifikasi.updateStatus', $biodata->id), [
            'status' => 'verified',
        ]);
        $response->assertRedirect(route('admin.verifikasi.index'));
        $response->assertSessionHas('success');

        // 8. Check that a Kartu Kuning is generated
        $user->refresh();
        $this->assertNotNull($user->kartuKuning, 'Kartu Kuning was not generated after verification. Kartu count: '.KartuKuning::count());
        $this->assertEquals('verified', $user->biodata->fresh()->status_verifikasi, 'Biodata status is not verified after update. Current status: '.$user->biodata->fresh()->status_verifikasi);

        // 9. Download the Kartu Kuning as the user
        $this->actingAs($user);

        $kartuKuning = $user->fresh()->kartuKuning;
        $this->assertNotNull($kartuKuning, 'Kartu Kuning not found when trying to download as user.');

        // Try downloading via the self route (for logged-in user's own kartu)
        $response = $this->get(route('kartu.download.self'));
        $response->assertHeader('Content-Type', 'application/pdf', 'Download did not return PDF content type.');
        $response->assertOk('Download did not return 200 OK.');
    }
}
