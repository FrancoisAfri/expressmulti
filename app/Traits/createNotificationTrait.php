<?php

namespace App\Traits;

use App\Models\BookingNotification;
use App\Models\Notification;

trait createNotificationTrait
{

    /**
     * @var Notification
     */
    private $notification;

    /**
     * @param Notification $notification
     */
    public function __construct(BookingNotification $notification)
    {
        $this->notification = $notification;
    }


    public function persistNotification(
        $role_id,
        $name,
        $email,
        $title,
        $message,
        $notifiable_type,
        $notifiable_id,
        $url
    )
    {
        BookingNotification::create(
            [
                'role_id' => $role_id ?? null,
                'name' => $name ?? null,
                'email' => $email ?? null,
                'title' => $title ?? null,
                'message' => $message ?? null,
                'notifiable_type' => $notifiable_type ?? null,
                'notifiable_id' => $notifiable_id ?? null,
                'url' => $url ?? null,
                'read_at' => null,
            ]
        );



    }

}
