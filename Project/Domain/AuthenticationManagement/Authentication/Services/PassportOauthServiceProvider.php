<?php

namespace Project\Domain\AuthenticationManagement\Authentication\Services;

use Project\Domain\AuthenticationManagement\Authentication\Services\Interfaces\OauthServiceProviderInterface;
use Project\Infrastructure\Secondary\Database\UserManagement\User\ModelDao\UserAuth;
use Project\UserInterface\Http\Interfaces\HttpClientInterface;

class PassportOauthServiceProvider implements OauthServiceProviderInterface
{
   /*
    * Authenticated user
    */
    private $userAuth;

    /*
    * Client Id
    */
    private $clientId;

    /*
     * Client secret
     */
    private $clientSecret;

    /*
     * Endpoint
     */
    private $endpoint;

    /**
     * Guzzle Http
     * @var HttpClientInterface
     */
    private $http;

    /**
     * PassportOauthServiceProvider constructor.
     * @param UserAuth $userAuth
     */
    public function __construct (UserAuth $userAuth)
    {
        $this->clientId = config('passport.passport_client.id');
        $this->clientSecret = config('passport.passport_client.secret');
        $this->endpoint = config('passport.passport_client.endpoint');
        $this->userAuth = $userAuth;
        $this->http = app()->make(HttpClientInterface::class);
    }

    /**
     *  Used to generate access token for oauth client
     * @param UserAuth $userAuth
     * @return mixed
     */
    public function generateAccessToken(UserAuth $userAuth): array
    {
        // Request to generate access token
        return json_decode((string) $this->http->request('POST', $this->endpoint, [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'username' => $userAuth->auth_id,
                'password' => 'password_' . $userAuth->auth_id,
                'scope' => '',
            ]
        ])->getBody(), true) ?? [];
    }
}
