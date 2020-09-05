<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndexUsersTest extends TestCase
{
	use RefreshDatabase;

    /**
     * Test listing users
     *
     * @return void
     */
    public function testUsersListing()
    {
        $response = $this->withHeaders([
		])->getJson('/api/users', []);

        $response->assertStatus(200);
        $response->assertJsonPath('per_page', config('test.pagination'));
    }
}
