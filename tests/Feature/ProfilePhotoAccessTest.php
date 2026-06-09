<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfilePhotoAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_access_their_photo(): void
    {
        $user = User::factory()->create(['role' => 'user', 'profile_photo_path' => 'photos/test.jpg']);
        Storage::disk('public')->put('photos/test.jpg', 'fake');

        $response = $this->actingAs($user)->get(route('profile.photo', ['user' => $user->id]));

        $response->assertOk();
    }

    public function test_admin_can_access_any_photo(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user', 'profile_photo_path' => 'photos/other.jpg']);
        Storage::disk('public')->put('photos/other.jpg', 'fake');

        $response = $this->actingAs($admin)->get(route('profile.photo', ['user' => $user->id]));

        $response->assertOk();
    }

    public function test_other_user_cannot_access_someone_elses_photo(): void
    {
        $user1 = User::factory()->create(['role' => 'user']);
        $user2 = User::factory()->create(['role' => 'user', 'profile_photo_path' => 'photos/private.jpg']);
        Storage::disk('public')->put('photos/private.jpg', 'fake');

        $response = $this->actingAs($user1)->get(route('profile.photo', ['user' => $user2->id]));

        $response->assertForbidden();
    }

    public function test_photo_returns_404_when_file_missing(): void
    {
        $user = User::factory()->create(['role' => 'user', 'profile_photo_path' => 'photos/missing.jpg']);

        $response = $this->actingAs($user)->get(route('profile.photo', ['user' => $user->id]));

        $response->assertNotFound();
    }

    public function test_atasan_can_access_any_photo(): void
    {
        $atasan = User::factory()->create(['role' => 'atasan']);
        $user = User::factory()->create(['role' => 'user', 'profile_photo_path' => 'photos/atasan-test.jpg']);
        Storage::disk('public')->put('photos/atasan-test.jpg', 'fake');

        $response = $this->actingAs($atasan)->get(route('profile.photo', ['user' => $user->id]));

        $response->assertOk();
    }

    public function test_guest_cannot_access_photo(): void
    {
        $user = User::factory()->create(['role' => 'user', 'profile_photo_path' => 'photos/guest.jpg']);
        Storage::disk('public')->put('photos/guest.jpg', 'fake');

        $response = $this->get(route('profile.photo', ['user' => $user->id]));

        $response->assertRedirect(route('login'));
    }

    public function test_photo_returns_404_when_path_is_empty(): void
    {
        $user = User::factory()->create(['role' => 'user', 'profile_photo_path' => null]);

        $response = $this->actingAs($user)->get(route('profile.photo', ['user' => $user->id]));

        $response->assertNotFound();
    }
}
