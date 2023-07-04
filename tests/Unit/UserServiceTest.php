<?php

namespace Tests\Unit;

use App\Services\UserService;
use Database\Seeders\DefaultUserSeeder;
use Tests\TestCase;

class UserServiceTest extends TestCase {

    public function setUp(): void {
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * A basic unit test example.
     */
    public function test_findVerifiedUserByEmail(): void {

        // we expect user data in our database from the defaultuserseeder!
        $userService = new UserService();
        $user = $userService->findVerifiedUserByEmail(DefaultUserSeeder::DEFAULT_EMAIL);
        $this->assertNotNull($user);

        // we try a non-existing user
        $user = $userService->findVerifiedUserByEmail(DefaultUserSeeder::DEFAULT_EMAIL . 'make_it_fail');
        $this->assertNull($user);

    }
}
