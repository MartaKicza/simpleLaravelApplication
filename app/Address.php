<?php

namespace App;

use App\Events\AddressDeleted;
use App\Events\AddressSaved;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'region', 'city', 'country', 'code', 'street', 'number'
    ];

	public function logs()
	{
		return $this->morphMany('App\Log', 'loggable');
	}

	/**
	 * The event map for the model.
	 *
	 * @var array
	 */
	protected $dispatchesEvents = [
		'saved' => AddressSaved::class,
		'deleted' => AddressDeleted::class,
	];
}
