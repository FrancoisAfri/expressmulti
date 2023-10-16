<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\regitserPatientNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;

class SendNewPatientRegistrationNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $admins =   User::whereHas('roles', function($q){
            $q->where('name', 'Admin');
        })->get();

        Notification::send($admins,regitserPatientNotification($event->user));
    }
}
