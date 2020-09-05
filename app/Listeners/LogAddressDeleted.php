<?php

namespace App\Listeners;

use App\Events\AddressDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogAddressDeleted
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
     * @param  AddressDeleted  $event
     * @return void
     */
    public function handle(AddressDeleted $event)
    {
        $event->address->logs()->create(['method' => 'delete']);
    }
}
