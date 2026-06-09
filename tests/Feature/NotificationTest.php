<?php

namespace Tests\Feature;

use App\Models\NotificationE;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_mark_notification_as_read(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $notification = NotificationE::create([
            'user_id' => $user->id,
            'judul' => 'Test Notifikasi',
            'pesan' => 'Ini adalah pesan notifikasi.',
            'status_baca' => false,
            'tipe' => 'status_update',
        ]);

        $this->actingAs($user)->patch(route('notifikasi.read', $notification));

        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'status_baca' => true,
        ]);
    }

    public function test_user_cannot_mark_other_users_notification_as_read(): void
    {
        $owner = User::factory()->create(['role' => 'user']);
        $other = User::factory()->create(['role' => 'user']);
        $notification = NotificationE::create([
            'user_id' => $owner->id,
            'judul' => 'Test Notifikasi',
            'pesan' => 'Pesan rahasia.',
            'status_baca' => false,
            'tipe' => 'status_update',
        ]);

        $response = $this->actingAs($other)->patch(route('notifikasi.read', $notification));

        $response->assertForbidden();
    }

    public function test_notifikasi_index_shows_user_notifications(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        NotificationE::create([
            'user_id' => $user->id,
            'judul' => 'Notif 1',
            'pesan' => 'Pesan 1',
            'status_baca' => false,
            'tipe' => 'status_update',
        ]);
        NotificationE::create([
            'user_id' => $user->id,
            'judul' => 'Notif 2',
            'pesan' => 'Pesan 2',
            'status_baca' => true,
            'tipe' => 'status_update',
        ]);

        $response = $this->actingAs($user)->get(route('notifikasi.index'));

        $response->assertOk();
        $response->assertSee('Notif 1');
        $response->assertSee('Notif 2');
    }
}
