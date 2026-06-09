<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BiodataStatusChanged
{
    use Dispatchable, SerializesModels;

    public $user;

    public $newStatus;

    public $oldStatus;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, string $newStatus, ?string $oldStatus = null)
    {
        $this->user = $user;
        $this->newStatus = $newStatus;
        $this->oldStatus = $oldStatus;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [];
    }
}
