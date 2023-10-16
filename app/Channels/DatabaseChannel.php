<?php

namespace App\Channels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Channels\DatabaseChannel as IlluminateDatabaseChannel;
class DatabaseChannel extends IlluminateDatabaseChannel
{


    /**
     * Send the given notification.
     *
     * @param mixed                                  $notifiable
     * @param Notification $notification
     *
     * @return Model
     */
    public function buildPayload($notifiable, Notification $notification): Model
    {

        return $notifiable->routeNotificationFor('database')->create([
            'id'      => $notification->id,
            'type'    => get_class($notification),
            'name'=> $notifiable->name ?? null,
            'email'=> $notifiable->email ?? null,
            'data'    => $this->getData($notifiable, $notification),
            'read_at' => null,
        ]);
    }
}
