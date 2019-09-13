<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class AuthenticationTest extends TestCase
{
    use DatabaseTransactions;
    /**
    @test
     */
    public function register_new_user(){
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('POST','/api/register', [
            'name' => 'Midoriya Deku',
            'email' => 'midoriya@ua.com',
            'password' => '123456789',
        ]);
        $response->assertJsonStructure(['user'=>['id','name','email', 'created_at']])
                 ->assertStatus(200);
    }

    /**
    @test
     */
    public function unregistered_try_to_login(){
        $this->withoutExceptionHandling();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('POST','/api/myLogin', [
            'username' => 'tanjiro@laravel.com',
            'password' => 'secret'
        ]);
        $response->assertStatus(422);
    }
    /**
    @test
     */
    public function registered_try_to_login(){
        $this->withoutExceptionHandling();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('POST','/api/myLogin', [
            'email' => 'arifzuhair@laravel.com',
            'password' =>'123456789'
        ]);
        $response->assertStatus(200);
    }
}
