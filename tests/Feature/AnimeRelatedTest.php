<?php

namespace Tests\Feature;

use App\Anime;
use Faker\Factory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AnimeRelatedTest extends TestCase
{
    use RefreshDatabase;


    /**
    @test
     */
    public function able_to_retrieve_all_anime(){
        $this->withoutExceptionHandling();
        $faker = Factory::create();
        $response = $this->json('GET','/api/anime', [
            'title' => 'My Hero Academia',
            'genre' => 'shounen',
            'runtime' => '30min'
        ]);
        $response->assertStatus(201);
    }

    /**
        @test
     */
    public function able_to_add_anime(){
        $this->withoutExceptionHandling();

        $response = $this->json('POST','/api/anime', [
            'title' => 'My Hero Academia',
            'genre' => 'shounen',
            'runtime' => '30min'
        ]);
        $response->assertStatus(201);
    }

    /**
    @test
     */
    public function able_to_get_anime(){
        $this->withoutExceptionHandling();
        $this->json('POST','/api/anime', [
            'title' => 'My Hero Academia',
            'genre' => 'shounen',
            'runtime' => '30min'
        ]);

        $anime = Anime::latest()->first();
        $response = $this->json('GET', 'api/anime/'.$anime->id);
        $response->assertStatus(302);
    }
    /**
    @test
     */
    public function able_to_update_anime(){
        $this->withoutExceptionHandling();
        $this->json('POST','/api/anime', [
            'title' => 'My Hero Academia',
            'genre' => 'shounen',
            'runtime' => '30min'
        ]);

        $anime = Anime::latest()->first();
        $response = $this->json('PUT', 'api/anime/'.$anime->id, [
            'title' => 'Fire Force',
            'genre' => 'Magic',
            'runtime' => '45min'
        ]);
        $response->assertOk();
    }

    /**
    @test
     */
    public function able_to_delete_anime(){
        $this->withoutExceptionHandling();
        $this->json('POST','/api/anime', [
            'title' => 'My Hero Academia',
            'genre' => 'shounen',
            'runtime' => '30min'
        ]);

        $anime = Anime::latest()->first();
        $response = $this->json('DELETE', 'api/anime/'.$anime->id);
        $response->assertOk();
    }

}
