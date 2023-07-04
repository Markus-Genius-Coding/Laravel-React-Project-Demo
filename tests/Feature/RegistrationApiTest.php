<?php

namespace Tests\Feature;

use App\Services\UserService;
use Database\Seeders\DefaultUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class RegistrationApiTest extends TestCase {
    /**
     * A basic feature test example.
     */
    public function test_successRegistration(): void {

        $email = str_replace('\\', '', 'make_it_work_for_' . __CLASS__ . __LINE__ . '_' . DefaultUserSeeder::DEFAULT_EMAIL);
        $response = $this->post('/api/user/register', [
            'firstname' => 'Max',
            'lastname' => 'Muster',
            'email' => $email,
            'password' => DefaultUserSeeder::DEFAULT_PASSWORD,
            'confirmed_password' => DefaultUserSeeder::DEFAULT_PASSWORD,
        ]);
        $response->assertStatus(200);
        $userService = new UserService();
        $this->assertNotNull($userService->findVerifiedUserByEmail($email));

    }

    public function test_failedRegistration() {

        // we expect user data in our database from the defaultuserseeder!
        $response = $this->post('/api/user/register', [
            'firstname' => 'Max',
            'lastname' => 'Muster',
            'email' => DefaultUserSeeder::DEFAULT_EMAIL,
            'password' => DefaultUserSeeder::DEFAULT_PASSWORD,
            'confirmed_password' => DefaultUserSeeder::DEFAULT_PASSWORD,
        ]);
        $response->assertStatus(400);
        $response->assertJsonStructure([
            'status' => [
                'code',
                'msg'
            ]
        ]);
    }
}
