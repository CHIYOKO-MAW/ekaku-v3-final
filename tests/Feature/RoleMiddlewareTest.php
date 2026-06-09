<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_user_cannot_access_admin_route(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertForbidden();
    }

    public function test_user_cannot_access_atasan_route(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('atasan.dashboard'));

        $response->assertForbidden();
    }

    public function test_admin_cannot_access_atasan_route(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('atasan.dashboard'));

        $response->assertForbidden();
    }

    public function test_atasan_cannot_access_admin_route(): void
    {
        $atasan = User::factory()->create(['role' => 'atasan']);

        $response = $this->actingAs($atasan)->get(route('admin.dashboard'));

        $response->assertForbidden();
    }

    public function test_user_cannot_access_status_pekerjaan_without_user_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('status.index'));

        $response->assertForbidden();
    }

    public function test_admin_can_access_admin_route(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
    }

    public function test_atasan_can_access_atasan_route(): void
    {
        $atasan = User::factory()->create(['role' => 'atasan']);

        $response = $this->actingAs($atasan)->get(route('atasan.dashboard'));

        $response->assertOk();
    }

    public function test_user_can_access_user_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('dashboard.user'));

        $response->assertOk();
    }
}
