<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AtasanRekapTest extends TestCase
{
    use RefreshDatabase;

    public function test_atasan_can_access_rekap_admin(): void
    {
        $atasan = User::factory()->create(['role' => 'atasan']);
        User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($atasan)->get(route('atasan.rekap-admin'));

        $response->assertOk();
    }

    public function test_non_atasan_cannot_access_rekap_admin(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('atasan.rekap-admin'));

        $response->assertForbidden();
    }

    public function test_atasan_can_access_atasan_dashboard(): void
    {
        $atasan = User::factory()->create(['role' => 'atasan']);

        $response = $this->actingAs($atasan)->get(route('atasan.dashboard'));

        $response->assertOk();
    }

    public function test_rekap_admin_shows_admin_list(): void
    {
        $atasan = User::factory()->create(['role' => 'atasan']);
        User::factory()->create(['role' => 'admin', 'name' => 'Admin Satu']);
        User::factory()->create(['role' => 'admin', 'name' => 'Admin Dua']);

        $response = $this->actingAs($atasan)->get(route('atasan.rekap-admin'));

        $response->assertOk();
        $response->assertSee('Admin Satu');
        $response->assertSee('Admin Dua');
    }
}
