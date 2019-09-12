<?php

namespace Tests\Feature;

use App\Anime;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AnimeRelatedTest extends TestCase
{
    use DatabaseTransactions;
    protected function loginFunction($email='arifzuhair@laravel.com', $password='123456789'){
        $loginResponse = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post('/api/myLogin', [
            'email' => $email,
            'password' => $password
        ]);
        return $loginResponse;
    }
    /**
    @test
     */
    public function able_to_retrieve_all_anime(){
        $this->withoutExceptionHandling();
        $registerResponse = $this->json('POST', '/api/register', [
            'name' => 'Test From TestCase',
            'email' => 'test@test.com',
            'password' => 'secret'
        ]);
        $registerResponse->assertStatus(200);
        $loginResponse = $this->loginFunction('test@test.com', 'secret');
        $dataReturned = json_decode($loginResponse->getContent());
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$dataReturned->access_token
        ])->json('GET','/api/anime');
        $response->assertStatus(200);
    }
    /**
    @test
     */
    public function not_able_to_retrieve_all_anime(){
//        $this->withoutExceptionHandling();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('GET','/api/anime');
        $response->assertStatus(401);
    }

    /**
        @test
     */
    public function able_to_add_anime(){
//        $this->withoutExceptionHandling();
        $loginResponse = $this->loginFunction();
        $loginData = json_decode($loginResponse->getContent());
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization'=> 'Bearer '.$loginData->access_token
        ])->json('POST','/api/anime', [
            'title' => 'My Hero Academia',
            'genre' => 'shounen',
            'runtime' => '30min'
        ]);
        $response->assertStatus(201);
    }

    /**
    @test
     */
    public function not_able_to_add_anime(){
        $response = $this->json('POST','/api/anime', [
            'title' => 'My Hero Academia',
            'genre' => 'shounen',
            'runtime' => '30min'
        ]);
        $response->assertStatus(401);
    }

    /**
    @test
     */
    public function able_to_get_anime(){
        $login = $this->loginFunction();
        $loginData = json_decode($login->getContent());
        $anime = Anime::latest()->first();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$loginData->access_token
        ])->json('GET', 'api/anime/'.$anime->id);
        $response->assertStatus(302);
    }

    /**
    @test
     */
    public function not_able_to_get_anime(){
        $anime = Anime::latest()->first();
        $response = $this->json('GET', 'api/anime/'.$anime->id);
        $response->assertStatus(401);
    }

    /**
    @test
     */
    public function able_to_update_anime(){
        $this->withoutExceptionHandling();
        $loginResponse = $this->loginFunction();
        $loginData = json_decode($loginResponse->getContent());
        $anime = Anime::latest()->first();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$loginData->access_token
        ])->json('PUT', 'api/anime/'.$anime->id, [
            'title' => 'Fire Force XXL',
            'genre' => 'Magic',
            'runtime' => '45min'
        ]);
        $response->assertOk();
    }

    /**
    @test
     */
    public function not_able_to_update_anime(){
//        $this->withoutExceptionHandling();
        $anime = Anime::latest()->first();
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->json('PUT', 'api/anime/'.$anime->id, [
            'title' => 'Fire Force',
            'genre' => 'Magic',
            'runtime' => '45min'
        ]);
        $response->assertStatus(401);
    }

    /**
    @test
     */
    public function not_able_to_delete_anime(){
//        $this->withoutExceptionHandling();
        $this->json('POST','/api/anime', [
            'title' => 'My Hero Academia',
            'genre' => 'shounen',
            'runtime' => '30min'
        ]);

        $anime = Anime::latest()->first();
        $response = $this->json('DELETE', 'api/anime/'.$anime->id);
        $response->assertStatus(401);
    }

}
