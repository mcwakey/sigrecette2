<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FileReadyNotification extends Notification
{
    use Queueable;
    /**
     * @param array $filePath
     */
    public function __construct(private string $fileName,private int $user_id)
    {
    }
    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'file_is_ready',
            'user_id' => $this->user_id,
            'file_name' => $this->fileName,
            'is_read' => false,
        ];
    }
}
