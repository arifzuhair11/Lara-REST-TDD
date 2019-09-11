<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationTest extends TestCase
{
//    use RefreshDatabase;
    /**
    @test
     */
    public function not_allowed_to_create(){
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('POST','/api/anime', [
            'title' => 'My Hero Academia',
            'genre' => 'shounen',
            'runtime' => '30min'
        ]);
        $response->assertStatus(401);
    }
    /**
    @test
     */
    public function unregistered_try_to_login(){
        $this->withoutExceptionHandling();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('POST','/api/login', [
            'username' => 'tanjiro@laravel.com',
            'password' => 'secret'
        ]);
        $response->assertStatus(401);
    }
    /**
    @test
     */
    public function registered_try_to_login(){
        $this->withoutExceptionHandling();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('POST','/api/login', [
            'email' => 'arifzuhair@laravel.com',
            'password' =>'secret'
        ]);
        $response->assertStatus(200);
    }
}
