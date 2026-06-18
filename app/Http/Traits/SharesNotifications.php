<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

trait SharesNotifications
{
    protected function shareNotifications(): void
    {
        $user = auth()->user();
        if (! $user) {
            return;
        }

        $userId = $user->id;
        $unreadNotes = Cache::remember("nav.{$userId}.unread", 60, function () use ($user) {
            return $user->notificationsE()->where('status_baca', false)->count();
        });
        $latestNotes = Cache::remember("nav.{$userId}.latest", 60, function () use ($user) {
            return $user->notificationsE()->latest()->take(5)->get();
        });

        View::share('unreadNotes', $unreadNotes);
        View::share('latestNotes', $latestNotes);
    }
}
