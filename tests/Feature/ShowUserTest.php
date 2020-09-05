<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class ShowUserTest extends TestCase
{
	use RefreshDatabase;

    /**
     * Test displaying user without addresses
     *
     * @return void
     */
    public function testShowUserWithoutAddresses()
    {
		$user = factory(User::class)->state('lecturer')->create();
        $response = $this->withHeaders([
		])->getJson("/api/user/{$user->id}", []);

		$response->assertStatus(200)
		   ->assertJson(["user" => $user->toArray()]);
    }

    /**
     * Test displaying user with addresses
     *
     * @return void
     */
    public function testShowUserWithAddresses()
    {
		$user = factory(User::class)->state('administration_worker')->create();
        $response = $this->withHeaders([
		])->getJson("/api/user/{$user->id}", []);

		$response->assertStatus(200)
		   ->assertJson(["user" => $user->toArray()])
		   ->assertJsonPath("user.address.id", $user->address_id)
		   ->assertJsonPath("user.correspondal_address.id", $user->correspondal_address_id);
	}
}
