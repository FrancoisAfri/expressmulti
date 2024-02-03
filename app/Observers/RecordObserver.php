<?php

namespace App\Observers;

use App\Models\EventsServices;

use App\Events\NewRecordAdded;

class RecordObserver
{
    /**
     * Handle the EventsServices "created" event.
     *
     * @param  \App\Models\EventsServices  $eventsServices
     * @return void
     */
    public function created(EventsServices $eventsServices)
    {
        event(new NewRecordAdded($record));
    }

    /**
     * Handle the EventsServices "updated" event.
     *
     * @param  \App\Models\EventsServices  $eventsServices
     * @return void
     */
    public function updated(EventsServices $eventsServices)
    {
        //
    }

    /**
     * Handle the EventsServices "deleted" event.
     *
     * @param  \App\Models\EventsServices  $eventsServices
     * @return void
     */
    public function deleted(EventsServices $eventsServices)
    {
        //
    }

    /**
     * Handle the EventsServices "restored" event.
     *
     * @param  \App\Models\EventsServices  $eventsServices
     * @return void
     */
    public function restored(EventsServices $eventsServices)
    {
        //
    }

    /**
     * Handle the EventsServices "force deleted" event.
     *
     * @param  \App\Models\EventsServices  $eventsServices
     * @return void
     */
    public function forceDeleted(EventsServices $eventsServices)
    {
        //
    }
}
