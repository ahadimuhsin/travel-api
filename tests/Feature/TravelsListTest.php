<?php

namespace Tests\Feature;

use App\Models\Travel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TravelsListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_travels_list_returns_pagination(): void
    {
        Travel::factory(16)->create(['is_public' => true]);

        $response = $this->get('/api/v1/travels');

        $response->assertStatus(200);

        //check apakah data tersebut mereturn 15 data
        $response->assertJsonCount(15, 'data');

        //cek apakah ada 2 halaman
        $response->assertJsonPath('meta.last_page', 2);
    }

    public function test_travels_list_shows_only_public_records()
    {
        $nonPublic = Travel::factory()->create(['is_public' => false]);
        $public = Travel::factory()->create(['is_public' => true]);

        $response = $this->get('/api/v1/travels');

        $response->assertStatus(200);

        //cek apakah hanya public record yg tampil
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['id' => $public->id]);
        $response->assertJsonMissing(['id' => $nonPublic->id]);
    }
}
