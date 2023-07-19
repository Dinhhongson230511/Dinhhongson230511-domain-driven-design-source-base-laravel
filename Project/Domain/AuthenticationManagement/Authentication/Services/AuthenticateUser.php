<?php

namespace Project\Domain\AuthenticationManagement\Authentication\Services;

use Project\Domain\AuthenticationManagement\Authentication\Enums\OauthType;
use Project\Domain\AuthenticationManagement\Authentication\Factories\OauthServiceType;
use Project\Domain\AuthenticationManagement\Authentication\Services\Interfaces\ClientAuthenticateInterface;
use Project\Domain\UserManagement\User\Services\UserDomainService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Project\Infrastructure\Secondary\Database\UserManagement\User\ModelDao\UserAuth;
use Project\Infrastructure\Secondary\Database\UserManagement\User\Interfaces\EloquentUserAuthInterface;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateUser implements ClientAuthenticateInterface
{

    /*
     * Authenticated user
     */
    private UserAuth $userAuth;

    /**
     * @var UserDomainService
     */
    private UserDomainService $user;

    /**
     * @var EloquentUserAuthInterface
     */
    private EloquentUserAuthInterface $userAuthRepository;

    /**
     * AuthenticateUser constructor.
     * @param UserAuth $userAuth
     * @throws BindingResolutionException
     */
    public function __construct(UserAuth $userAuth)
    {
        $this->userAuth = $userAuth;
        $this->user = app()->make(UserDomainService::class);
        $this->userAuthRepository = app()->make(EloquentUserAuthInterface::class);
    }

    /**
     * Authenticate user
     *
     * @return AuthenticateUser
     */
    public function signIn(): AuthenticateUser
    {
        // Initialize Oauth service provider and get the access token
        $this->userAuth->token = OauthServiceType::instantiateOauthServiceProvider(
            OauthType::Passport,
            $this->userAuth
        )->generateAccessToken($this->userAuth);

        // Log when user logs in to the system
        $this->userAuthRepository->logUserLogin($this->userAuth);

        return $this;
    }

    /**
     * Redirect user after authentication
     *
     * @return array
     */
    public function respondAfterAuthentication(): array
    {
        return [
            'status' => Response::HTTP_OK,
            'message' => __('api_auth.user_login_successfully'),
            'data' => $this->user->retrieveUserDataAfterAuthentication($this->userAuth)
        ];
    }
}
