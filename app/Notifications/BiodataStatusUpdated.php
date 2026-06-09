<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BiodataStatusUpdated extends Notification
{
    use Queueable;

    public $status;

    public $oldStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $status, ?string $oldStatus = null)
    {
        $this->status = $status;
        $this->oldStatus = $oldStatus;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $statusLabel = ucfirst($this->status);
        $oldLabel = $this->oldStatus ? ucfirst($this->oldStatus) : 'belum terverifikasi';

        return (new MailMessage)
            ->line('Status Biodata Anda Telah Diperbarui')
            ->line("Status biodata Anda telah berubah dari \"$oldLabel\" menjadi \"$statusLabel\".")
            ->action('Lihat Detail Biodata', url('/profile'))
            ->line('Terima kasih!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
