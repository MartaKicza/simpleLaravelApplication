<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Address;

class DeleteUserTest extends TestCase
{
	use RefreshDatabase;

    /**
     * Lecturer deletion test.
     *
     * @return void
     */
    public function testDeleteLecturer()
    {
		$user = factory(User::class)->state('lecturer')->create();
		$response = $this->withHeaders([
		])->deleteJson("/api/user/{$user->id}", []);

		$response
			->assertStatus(200)
			->assertJson([
				"deleted"=> true
			]);

		$this->assertDeleted('users', ['id' => $user->id]);
    }

    /**
     * Administration worker deletion test.
     *
     * @return void
     */
    public function testDeleteAdministrationWorker()
    {
		$user = factory(User::class)->state('administration_worker')->create();
		$response = $this->withHeaders([
		])->deleteJson("/api/user/{$user->id}", []);

		$response
			->assertStatus(200)
			->assertJson([
				"deleted"=> true
			]);

		$this->assertDeleted('users', ['id' => $user->id]);
		$this->assertDeleted('addresses', ['id' => $user->address_id]);
		$this->assertDeleted('addresses', ['id' => $user->correspondal_address_id]);
    }

    /**
     * Both Lecturer and Administration worker deletion test.
     *
     * @return void
     */
    public function testDeleteLecturerAndAdministrationWorker()
    {
		$user = factory(User::class)->state('lecturer_administration_worker')->create();
		$response = $this->withHeaders([
		])->deleteJson("/api/user/{$user->id}", []);

		$response
			->assertStatus(200)
			->assertJson([
				"deleted"=> true
			]);

		$this->assertDeleted('users', ['id' => $user->id]);
		$this->assertDeleted('addresses', ['id' => $user->address_id]);
		$this->assertDeleted('addresses', ['id' => $user->correspondal_address_id]);
    }
}
