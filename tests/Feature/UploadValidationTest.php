<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UploadValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_upload_rejects_invalid_file_type(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->post(route('upload.store'), [
            'jenis_dokumen' => 'ktp',
            'file' => UploadedFile::fake()->create('document.txt', 100),
        ]);

        $response->assertSessionHasErrors('file');
    }

    public function test_upload_rejects_file_exceeding_max_size(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->post(route('upload.store'), [
            'jenis_dokumen' => 'ktp',
            'file' => UploadedFile::fake()->create('photo.jpg', 6000),
        ]);

        $response->assertSessionHasErrors('file');
    }

    public function test_upload_rejects_missing_jenis_dokumen(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->post(route('upload.store'), [
            'file' => UploadedFile::fake()->image('ktp.jpg'),
        ]);

        $response->assertSessionHasErrors('jenis_dokumen');
    }

    public function test_upload_rejects_invalid_jenis_dokumen(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->post(route('upload.store'), [
            'jenis_dokumen' => 'sim',
            'file' => UploadedFile::fake()->image('ktp.jpg'),
        ]);

        $response->assertSessionHasErrors('jenis_dokumen');
    }

    public function test_upload_kk_succeeds(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->post(route('upload.store'), [
            'jenis_dokumen' => 'kk',
            'file' => UploadedFile::fake()->image('kk.jpg', 200, 200),
        ]);

        $response->assertRedirect(route('profile.edit').'#berkas');
        $response->assertSessionHas('success');
        $this->assertEquals(1, $user->uploads()->count());
    }

    public function test_upload_sertifikat_succeeds(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->post(route('upload.store'), [
            'jenis_dokumen' => 'sertifikat',
            'file' => UploadedFile::fake()->create('sertifikat.pdf', 500),
        ]);

        $response->assertRedirect(route('profile.edit').'#berkas');
        $response->assertSessionHas('success');
        $this->assertEquals(1, $user->uploads()->count());
    }

    public function test_upload_succeeds_with_valid_input(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->post(route('upload.store'), [
            'jenis_dokumen' => 'ktp',
            'file' => UploadedFile::fake()->image('ktp.jpg', 200, 200),
        ]);

        $response->assertRedirect(route('profile.edit').'#berkas');
        $response->assertSessionHas('success');
        $this->assertEquals(1, $user->uploads()->count());
    }
}
