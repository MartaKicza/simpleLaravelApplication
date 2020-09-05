<?php

namespace App\Listeners;

use App\Events\AddressSaved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogAddressSaved
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
     * @param  AddressSaved  $event
     * @return void
     */
    public function handle(AddressSaved $event)
    {
        $event->address->logs()->create(['method' => 'save', 'data' => $event->address->toJson()]);
    }
}
