<?php

namespace App\Listeners;

use App\Events\NewRecordAdded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PlaySoundAndRefreshWidget implements ShouldQueue
{
	 use Dispatchable, InteractsWithSockets, SerializesModels;
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
     * @param  \App\Events\NewRecordAdded  $event
     * @return void
     */
    public function handle(NewRecordAdded $event)
    {
        // You can access the record using $event->record

        // Play sound logic (using JavaScript)
        $soundJs = <<<JS
            // Your JavaScript logic to play the sound
            console.log('Playing sound...');
        JS;

        // Refresh widget logic (using JavaScript)
        $refreshJs = <<<JS
            // Your JavaScript logic to refresh the widget
            console.log('Refreshing widget...');
        JS;

        // Broadcast the JavaScript events to the client-side
        broadcast(new \App\Events\PlaySoundAndRefreshWidget($soundJs, $refreshJs))
    }
}
