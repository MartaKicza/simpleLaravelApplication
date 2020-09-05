<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
	use RefreshDatabase;

    /**
     * Empty form test.
     *
     * @return void
     */
    public function testEmptyForm()
    {
		$response = $this->withHeaders([
		])->postJson('/api/user', []);

		$response
			->assertStatus(422)
			->assertJsonStructure([
				"errors"=> [
					"name",
					"lastname",
					"email",
					"password",
					"type_aw",
					"type_l"
				]
			]);
    }

    /**
     * Week password test.
     *
     * @return void
     */
    public function testWeekPassword()
    {
		$response = $this->withHeaders([
		])->postJson('/api/user', [
			"password" => 'abc123',
			"password_confirmation" => 'abc123'
		]);

		$response
			->assertStatus(422)
			->assertJsonValidationErrors(['password']);
    }


    /**
     * Strong password test.
     *
     * @return void
     */
    public function testStrongPassword()
    {
		$response = $this->withHeaders([
		])->postJson('/api/user', [
			"password" => 'ab123VC$',
			"password_confirmation" => 'ab123VC$'
		]);

		$response
			->assertStatus(422)
			->assertJsonMissingValidationErrors('password');
    }

    /**
     * No type selected.
     *
     * @return void
     */
    public function testNoTypeInForm()
    {
		$response = $this->withHeaders([
		])->postJson('/api/user', [
			"name" => "Test", 
			"lastname" => "Test", 
			"email" => "t@t.pl", 
			"password" => "123ASDasd!@#", 
			"password_confirmation" => "123ASDasd!@#", 
			"type_aw" => false, 
			"type_l" => false, 
		]);

		$response
			->assertStatus(422)
			->assertJsonStructure([
				"errors"=> [
					"type_aw",
					"type_l"
				]
			]);
    }


    /**
     * Empty addresses test.
     *
     * @return void
     */
    public function testEmptyAddressesForm()
    {
		$response = $this->withHeaders([
		])->postJson('/api/user', [
			"name" => "Test", 
			"lastname" => "Test", 
			"email" => "t@t.pl", 
			"password" => "123ASDasd!@#", 
			"password_confirmation" => "123ASDasd!@#", 
			"type_aw" => true, 
			"type_l" => false, 
		]);

		$response
			->assertStatus(422)
			->assertJsonStructure([
				"errors"=> [
					"address",
					"correspondal_address",
					"address.region",
					"address.city",
					"address.country",
					"address.code",
					"address.number",
					"correspondal_address.region",
					"correspondal_address.city",
					"correspondal_address.country",
					"correspondal_address.code",
					"correspondal_address.number",
				]
			]);
    }

    /**
     * Empty phone and number test.
     *
     * @return void
     */
    public function testEmptyPhoneAndNumberForm()
    {
		$response = $this->withHeaders([
		])->postJson('/api/user', [
			"name" => "Test", 
			"lastname" => "Test", 
			"email" => "t@t.pl", 
			"password" => "123ASDasd!@#", 
			"password_confirmation" => "123ASDasd!@#", 
			"type_aw" => 0, 
			"type_l" => 1, 
		]);

		$response
			->assertStatus(422)
			->assertJsonStructure([
				"errors" => [
					"phone",
					"education"
				]
			]);
    }

    /**
     * Test a lecturer creation.
     *
     * @return void
     */
    public function testLecturerCreation()
    {
		$response = $this->withHeaders([
		])->postJson('/api/user', [
			"name" => "Test", 
			"lastname" => "Test", 
			"email" => "t@t.pl", 
			"password" => "123ASDasd!@#", 
			"password_confirmation" => "123ASDasd!@#", 
			"type_l" => 1, 
			"type_aw" => 0, 
			"phone" => 'xxx',
			"education" => 'magister',
		]);

		$response
			->assertStatus(201)
			->assertJson([
				"created" => true
			]);
    }

    /**
     * Test a administration worker creation.
     *
     * @return void
     */
    public function testAdministrationWorkerCreation()
    {
		$response = $this->withHeaders([
		])->postJson('/api/user', [
			"name" => "Test", 
			"lastname" => "Test", 
			"email" => "t@t.pl", 
			"password" => "123ASDasd!@#", 
			"password_confirmation" => "123ASDasd!@#", 
			"type_aw" => 1, 
			"type_l" => 0, 
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
		]);

		$response
			->assertStatus(201)
			->assertJson([
				"created" => true
			]);
    }

	/**
     * Test a administration worker and lecturer creation.
     *
     * @return void
     */
    public function testAdministrationWorkerAndLecturerCreation()
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

		$response
			->assertStatus(201)
			->assertJson([
				"created" => true
			]);
    }
}
