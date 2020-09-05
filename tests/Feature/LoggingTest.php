<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Address;

class LoggingTest extends TestCase
{
	use RefreshDatabase;

    /**
     * Testing if creating a User with Addresses write logs.
     *
     * @return void
     */
    public function testCreatingUserWithAddresses()
    {
		$response = $this->withHeaders([
		])->postJson('/api/user', [
			"name" => "Test", 
			"lastname" => "Test", 
			"email" => "t@t.pl", 
			"password" => "123ASDasd!@#", 
			"password_confirmation" => "123ASDasd!@#", 
			"type_aw" => 1, 
			"type_l" => 1, 
			"phone" => "112223334",
			"education" => "inżynier",
			"address" => [
				"region" => "Wielkopolska", 
				"city" => "Poznań", 
				"code" => "61-616", 
				"street" => "os. Łokietka", 
				"number" => "7G/66",
				"country" => "Polska"
			],
			"correspondal_address" => [
				"region" => "Wielkopolska", 
				"city" => "Poznań", 
				"code" => "61-616", 
				"street" => "os. Łokietka", 
				"number" => "7G/66",
				"country" => "Polska"
			]
		]);
		$user = User::find($response->getData()->id);

		$this->assertDatabaseHas('logs', [
			'method' => 'save',
			'loggable_type' => 'App\User',
			'loggable_id' => $user->id,
		]);	

		$this->assertDatabaseHas('logs', [
			'method' => 'save',
			'loggable_type' => 'App\Address',
			'loggable_id' => $user->address_id,
		]);	

		$this->assertDatabaseHas('logs', [
			'method' => 'save',
			'loggable_type' => 'App\Address',
			'loggable_id' => $user->correspondal_address_id,
		]);	
    }

    /**
     * Testing if updating a User with Addresses write logs.
     *
     * @return void
     */
    public function testUpdatingUserWithAddresses()
    {
		$user = factory(User::class)->state('administration_worker')->create();
		$data = [
			"name" => "Test", 
			"lastname" => "Test", 
			"email" => "t@t.pl", 
			"password" => "123ASDasd!@#", 
			"password_confirmation" => "123ASDasd!@#", 
			'type_aw' => 1,
			'type_l' => 0,
			"address" => [
				"region" => "Wielkopolska", 
				"city" => "Poznań", 
				"code" => "61-610", 
				"street" => "os. Łokietka", 
				"number" => "8G/11",
				"country" => "Polska"
			],
			"correspondal_address" => [
				"region" => "Wielkopolska", 
				"city" => "Poznań", 
				"code" => "61-610", 
				"street" => "os. Łokietka", 
				"number" => "8G/11",
				"country" => "Polska"
			]
		];
		$response = $this->withHeaders([
		])->putJson("/api/user/{$user->id}", $data);

		$this->assertDatabaseHas('logs', [
			'method' => 'save',
			'loggable_type' => 'App\User',
			'loggable_id' => $user->id,
		]);	

		$this->assertDatabaseHas('logs', [
			'method' => 'save',
			'loggable_type' => 'App\Address',
			'loggable_id' => $user->address_id,
		]);	

		$this->assertDatabaseHas('logs', [
			'method' => 'save',
			'loggable_type' => 'App\Address',
			'loggable_id' => $user->correspondal_address_id,
		]);	
    }

	/**
     * Testing if changing Administration Worker to Lecturer write logs with Address Deletion.
     *
     * @return void
     */
    public function testWriteDeletionLogsWithUserUpdate()
    {
		$user = factory(User::class)->state('lecturer_administration_worker')->create();
		$data = [
			"name" => "Test", 
			"lastname" => "Test", 
			"email" => "t@t.pl", 
			"password" => "123ASDasd!@#", 
			"password_confirmation" => "123ASDasd!@#", 
			'type_aw' => 0,
			'type_l' => 1,
			"phone" => '1234254353',
			'education' => 'magister',
		];
		$response = $this->withHeaders([
		])->putJson("/api/user/{$user->id}", $data);

		$this->assertDatabaseHas('logs', [
			'method' => 'save',
			'loggable_type' => 'App\User',
			'loggable_id' => $user->id,
		]);	

		$this->assertDatabaseHas('logs', [
			'method' => 'delete',
			'loggable_type' => 'App\Address',
			'loggable_id' => $user->address_id,
		]);	

		$this->assertDatabaseHas('logs', [
			'method' => 'delete',
			'loggable_type' => 'App\Address',
			'loggable_id' => $user->correspondal_address_id,
		]);	
    }

	/*
	 * Testing deletion logs.
     *
     * @return void
     */
    public function testWriteDeletionLogs()
    {
		$user = factory(User::class)->state('lecturer_administration_worker')->create();
		$response = $this->withHeaders([
		])->deleteJson("/api/user/{$user->id}", []);

		$this->assertDatabaseHas('logs', [
			'method' => 'delete',
			'loggable_type' => 'App\User',
			'loggable_id' => $user->id,
		]);	

		$this->assertDatabaseHas('logs', [
			'method' => 'delete',
			'loggable_type' => 'App\Address',
			'loggable_id' => $user->address_id,
		]);	

		$this->assertDatabaseHas('logs', [
			'method' => 'delete',
			'loggable_type' => 'App\Address',
			'loggable_id' => $user->correspondal_address_id,
		]);	
    }
}
