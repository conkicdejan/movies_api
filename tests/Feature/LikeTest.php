<?php

namespace Tests\Feature;

use App\Models\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class LikeTest extends TestCase
{

    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_like()
    {

        $payload = [
            'like' => 1,
        ];

        $response = $this->actingAs($this->user);

        $response = $this->json('put', '/api/movies/1', $payload);

        $this->assertDatabaseHas('movie_user', [
            'movie_id' => '1',
            'user_id' => $this->user->id,
            'like' => 1,
        ]);
    }

    public function test_user_can_like_againg()
    {

        $payload = [
            'like' => 1,
        ];

        $response = $this->actingAs($this->user);

        $response = $this->json('put', '/api/movies/1', $payload);
        // $response->dd();

        $response = $response['message'];

        $this->assertSame("Cannot like multiple times", $response);
    }
}
