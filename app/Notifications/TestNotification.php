<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TestNotification extends Notification
{
    use Queueable;

    protected array $data;

    /**
     * Create a new notification instance.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [
            'icon' => $this->data['icon'] ?? '/storage/demo/default.png',
            'body' => $this->data['body'] ?? 'This is a default notification message.',
            'link' => $this->data['link'] ?? '/dashboard',
            'user' => [
                'name' => $this->data['user']['name'] ?? 'Unknown User',
            ],
        ];
    }
}
