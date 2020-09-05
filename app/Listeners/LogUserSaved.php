<?php

namespace App\Listeners;

use App\Events\UserSaved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogUserSaved
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
     * @param  UserSaved  $event
     * @return void
     */
    public function handle(UserSaved $event)
    {
        $event->user->logs()->create([
            'method' => 'save',
            'data' => $event->user->makeHidden('address')->makeHidden('correspondalAddress')->toJson()
        ]);
    }
}
