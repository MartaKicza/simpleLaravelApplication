<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
	$type_aw = $faker->boolean; 
	$type_l = $faker->boolean; 
	if ($type_aw) {
		$address1 = factory(App\Address::class)->create();
		$address2 = factory(App\Address::class)->create();
	}
	if (!$type_aw && !$type_l) {
		$type_l = true;
	}
    return [
        'name' => $faker->firstName,
        'lastname' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => $faker->password,
        'type_aw' => $type_aw,
        'type_l' => $type_l,
		'phone' => $type_l ? $faker->phoneNumber : null,
		'education' => $type_l ? $faker->randomElement(config('test.education_degrees')) : null,
		'address_id' => $type_aw ? $address1->id : null,
		'correspondal_address_id' => $type_aw ? $address2->id : null,
    ];
});

$factory->state(App\User::class, 'lecturer', function ($faker) {
    return [
		'type_l' => 1,
		'type_aw' => 0,
		'phone' => $faker->phoneNumber,
		'education' => $faker->randomElement(config('test.education_degrees')),
		'address_id' => null,
		'correspondal_address_id' => null,
    ];
});

$factory->state(App\User::class, 'administration_worker', function ($faker) {
	$address1 = factory(App\Address::class)->create();
	$address2 = factory(App\Address::class)->create();
	return [
		'type_l' => false,
		'type_aw' => true,
		'phone' => null,
		'education' => null,
		'address_id' => $address1->id,
		'correspondal_address_id' => $address2->id,
    ];
});

$factory->state(App\User::class, 'lecturer_administration_worker', function ($faker) {
	$address1 = factory(App\Address::class)->create();
	$address2 = factory(App\Address::class)->create();
    return [
		'type_l' => true,
		'type_aw' => true,
		'phone' => $faker->phoneNumber,
		'education' => $faker->randomElement(config('test.education_degrees')),
		'address_id' => $address1->id,
		'correspondal_address_id' => $address2->id,
    ];
});
