<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\AuthService;
use App\Services\UserService;
use Database\Seeders\DefaultUserSeeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AuthServiceTest extends TestCase {

    public function setUp(): void {
        parent::setUp();
    }

    public function tearDown(): void {
        parent::tearDown();
    }

    /**
     * A basic unit test example.
     */
    public function test_getTokenAndRefreshToken(): void {

        $authService = new AuthService();
        $userService = new UserService();
        $user = $userService->findVerifiedUserByEmail(DefaultUserSeeder::DEFAULT_EMAIL);
        $authService->revokeAccessAndRefreshTokens($user->id);

        //testing wrong credentials
        $auth = Auth::attempt([
            'email' => 'blablub@mw-systems.com',
            'password' => DefaultUserSeeder::DEFAULT_PASSWORD,
        ]);
        $this->assertFalse($auth);

        //testing valid credentals
        $auth = Auth::attempt([
            'email' => DefaultUserSeeder::DEFAULT_EMAIL,
            'password' => DefaultUserSeeder::DEFAULT_PASSWORD,
        ]);
        $this->assertTrue($auth);

        //we create a valid accesstoken for the moment to test later if only one exists!
        $authService->getTokenAndRefreshToken(DefaultUserSeeder::DEFAULT_EMAIL, DefaultUserSeeder::DEFAULT_PASSWORD);
        $authService->getTokenAndRefreshToken(DefaultUserSeeder::DEFAULT_EMAIL, DefaultUserSeeder::DEFAULT_PASSWORD);

        $this->assertTrue($this->getAccessTokenCountForUser($user) == 2);

        //We revoke every token
        $authService->revokeAccessAndRefreshTokens($user->id);
        $this->assertTrue($this->getAccessTokenCountForUser($user) == 0);

        $authData = (array)json_decode($authService->getTokenAndRefreshToken(DefaultUserSeeder::DEFAULT_EMAIL, DefaultUserSeeder::DEFAULT_PASSWORD)->getContent());
        $this->assertTrue($this->getAccessTokenCountForUser($user) == 1);
        $this->assertArrayHasKey('token_type', $authData);
        $this->assertArrayHasKey('expires_in', $authData);
        $this->assertArrayHasKey('access_token', $authData);
        $this->assertArrayHasKey('refresh_token', $authData);

    }

    private function getAccessTokenCountForUser(User $user) : int {
        $result = DB::table('oauth_access_tokens')
            ->where('user_id', $user->id)
            ->where('revoked', false)
            ->count();
        return $result;
    }
 }
