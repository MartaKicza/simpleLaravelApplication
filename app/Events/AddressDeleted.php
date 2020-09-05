<?php

namespace App\Events;

use App\Address;

class AddressDeleted
{
    public $address;

    /**
     * Create a new event instance.
     *
     * @param  \App\Address  $address
     * @return void
     */
    public function __construct(Address $address)
    {
        $this->address = $address;
    }
}
