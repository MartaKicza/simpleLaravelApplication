<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Address;
use Faker\Generator as Faker;

$factory->define(Address::class, function (Faker $faker) {
    return [
        'region' => $faker->state,
        'city' => $faker->city,
        'code' => $faker->postcode,
        'street' => $faker->streetName,
        'number' => $faker->buildingNumber,
        'country' => $faker->country,
    ];
});
