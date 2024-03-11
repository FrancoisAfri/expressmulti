<?php

namespace App\Observers;

use App\Models\EventsServices;
//use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
class ServicesObserver
{
    /**
     * Handle the EventsServices "created" event.
     *
     * @param  \App\Models\EventsServices  $eventsServices
     * @return void
     */
    public function created(EventsServices $eventsServices)
    {
		//Session::put('event_session', true);
		// insert into table
		DB::table('events_sessions_check')->insert(['session_check' => '1']);
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
