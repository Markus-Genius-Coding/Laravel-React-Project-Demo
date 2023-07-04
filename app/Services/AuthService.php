<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Client as OClient;
use Symfony\Component\HttpFoundation\Response;

class AuthService {


    /**
     * @param string $email
     * @param string $password
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getTokenAndRefreshToken(string $email, string $password): Response {
        $oClient = OClient::where('password_client', 1)->first();
        $http = new Client;
        $response = $http->request('POST', 'http://localhost/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $oClient->id,
                'client_secret' => $oClient->secret,
                'username' => strtolower($email),
                'password' => $password,
                'scope' => '*',
            ],
        ]);

        $result = json_decode((string)$response->getBody(), true);

        return response()->json($result);
    }

    /**
     * @param string $userId
     * @return void
     */
    public function revokeAccessAndRefreshTokens(string $userId): void {

        $tokenRepository = app('Laravel\Passport\TokenRepository');
        $refreshTokenRepository = app('Laravel\Passport\RefreshTokenRepository');

        $tokens = DB::table('oauth_access_tokens')
            ->where('user_id', $userId)
            ->where('revoked', false)
            ->get();
        foreach ($tokens as $token) {
            $tokenRepository->revokeAccessToken($token->id);
            $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($token->id);
        }

    }

}
