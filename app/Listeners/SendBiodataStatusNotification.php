<?php

namespace App\Listeners;

use App\Events\BiodataStatusChanged;
use App\Notifications\BiodataStatusUpdated;
use Illuminate\Support\Facades\Notification;

class SendBiodataStatusNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BiodataStatusChanged $event): void
    {
        Notification::send($event->user, new BiodataStatusUpdated($event->newStatus, $event->oldStatus));
    }
}
