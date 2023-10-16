<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class BaseNotification extends Notification
{

    public $name;

    public $email;

    public function __construct($name,$email)
    {
        $this->name = $name;
        $this->email = $email;
    }

    public function via()
    {
        return ['database'];
    }


    /**
     * @param $notifiable
     * @return array
     */
    public function toDatabase($notifiable): array
    {
        return [
            //
        ];
    }
}
