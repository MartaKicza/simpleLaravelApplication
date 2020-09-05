<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\User;

class UpdateUserTest extends TestCase
{
	use RefreshDatabase;

    /**
     * Empty form test.
     *
     * @return void
     */
    public function testEmptyForm()
    {
		$user = factory(User::class)->state('lecturer')->create();
		$response = $this->withHeaders([
		])->putJson("/api/user/{$user->id}", []);

		$response
			->assertStatus(422)
			->assertJsonStructure([
				"errors"=> [
					"name",
					"lastname",
					"email",
					"type_aw",
					"type_l"
				]
			]);
    }

    /**
     * Missing type.
     *
     * @return void
     */
    public function testMissingType()
    {
		$user = factory(User::class)->state('lecturer')->create();
		$user->type_aw = 0;
		$user->type_l = 0;
		$response = $this->withHeaders([
		])->putJson("/api/user/{$user->id}", $user->toArray());

		$response
			->assertStatus(422)
			->assertJsonValidationErrors(['type_aw', 'type_l']);
    }

    /**
     * Check update all lecturer fields.
     *
     * @return void
     */
    public function testLecturerUpdate()
    {
		$user = factory(User::class)->state('lecturer')->create();
		$data = [
			"name" => "Test", 
			"lastname" => "Test", 
			"email" => "t@t.pl", 
			"password" => "123ASDasd!@#", 
			"password_confirmation" => "123ASDasd!@#", 
			"phone" => '1234254353',
			'education' => 'magister',
			'type_aw' => 0,
			'type_l' => 1,
		];
		$response = $this->withHeaders([
		])->putJson("/api/user/{$user->id}", $data);

		$response
			->assertStatus(200)
			->assertJson([
				"updated" => true
			]);

		$data['id'] = $user->id;
		unset($data['password']);
		unset($data['password_confirmation']);
		$this->assertDatabaseHas('users', $data);	
    }

    /**
     * Check update all Administration Worker fields.
     *
     * @return void
     */
    public function testAdministrationWorkerUpdate()
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

		$response
			->assertStatus(200)
			->assertJson([
				"updated" => true
			]);

		$this->assertDatabaseHas('addresses', $data['address']);	
		$this->assertDatabaseHas('addresses', $data['correspondal_address']);	

		$data['id'] = $user->id;
		unset($data['password']);
		unset($data['password_confirmation']);
		unset($data['address']);
		unset($data['correspondal_address']);
		$this->assertDatabaseHas('users', $data);	
    }

    /**
     * Check change lecturer type.
     *
     * @return void
     */
    public function testChangeLecturerType()
    {
		$user = factory(User::class)->state('lecturer_administration_worker')->create();
		$user->type_l = 0;
		$data = $user->toArray();
		$data['address'] = $user->address->toArray();
		$data['correspondal_address'] = $user->correspondal_address->toArray();

		$response = $this->withHeaders([
		])->putJson("/api/user/{$user->id}", $data);

		$response
			->assertStatus(200)
			->assertJson([
				"updated" => true
			]);

		$this->assertDatabaseHas('users', [
			'id' => $user->id,
			'phone' => null,
			'education' => null
		]);	
    }

	/**
     * Check change administration worker type.
     *
     * @return void
     */
    public function testChangeAdministrationWorkerType()
    {
		$user = factory(User::class)->state('lecturer_administration_worker')->create();
		$user->type_aw = 0;
		$user->phone = '121314242';
		$user->education = 'doktor';
		$data = $user->toArray();

		$response = $this->withHeaders([
		])->putJson("/api/user/{$user->id}", $data);

		$response
			->assertStatus(200)
			->assertJson([
				"updated" => true
			]);

		$this->assertDatabaseHas('users', [
			'id' => $user->id,
			'address_id' => null,
			'correspondal_address_id' => null
		]);	
		$this->assertDeleted('addresses', ['id' => $user->address_id]);
		$this->assertDeleted('addresses', ['id' => $user->correspondal_address_id]);
    }
}
