<?php

namespace Tests\Feature;

use Database\Seeders\DefaultUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthApiTest extends TestCase {
    /**
     * A basic feature test example.
     */
    public function test_successfulLogin(): void {

        // we expect user data in our database from the defaultuserseeder!
        $response = $this->post('/api/user/login', [
            'email' => DefaultUserSeeder::DEFAULT_EMAIL,
            'password' => DefaultUserSeeder::DEFAULT_PASSWORD,
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'authdata' => [
                'token_type',
                'expires_in',
                'access_token',
                'refresh_token',
            ],
            'userdata' => [
                'firstname',
                'lastname',
                'email',
                'email_verified_at',
                'activation_code',
                'id',
                'created_at',
                'updated_at',
            ]
        ]);
    }

    public function test_failedLogin() {
        $response = $this->post('/api/user/login', [
            'email' => DefaultUserSeeder::DEFAULT_EMAIL,
            'password' => DefaultUserSeeder::DEFAULT_PASSWORD . '_make_it_fail',
        ]);
        $response->assertStatus(401);
    }
}
