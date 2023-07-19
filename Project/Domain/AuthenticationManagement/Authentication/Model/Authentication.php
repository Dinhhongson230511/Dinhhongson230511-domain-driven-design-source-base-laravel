<?php

namespace Project\Domain\AuthenticationManagement\Authentication\Model;

use Project\Domain\AuthenticationManagement\Authentication\Enums\ClientType;
use Project\Domain\AuthenticationManagement\Authentication\Enums\UserAuthType;
use Project\Domain\AuthenticationManagement\Authentication\Factories\AuthenticationType;
use Project\Domain\AuthenticationManagement\Authentication\Services\Interfaces\AuthServiceInterface;
use Project\Domain\UserManagement\User\Services\UserDomainService;
use Project\Infrastructure\Secondary\Database\UserManagement\User\ModelDao\UserAuth;
use Project\UserInterface\Helpers\Utility;
use Project\UserInterface\ResponseCodes\ApiCodes;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Authentication implements AuthServiceInterface
{
    /*
    * @var UserDomainService
    */
    public UserDomainService $userService;

    /**
     * @var UserAuthType
     */
    public $authType;

    /*
   * @var Authentication ID
   */
    public $authId;

    /*
     * User Count
     */
    public $userCount;

    /**
     * BaseAuthService constructor.
     * @param string $authId
     * @param string $authType
     * @throws BindingResolutionException
     */
    public function __construct(
        string $authId,
        string $authType
    ) {
        $this->authId = $authId;
        $this->authType = $authType;
        $this->userService = app()->make(UserDomainService::class);
    }

    /**
     * Set the auth id of the user for authentication
     */
    protected function verifyDuplicateAccountScenario(): bool
    {
        // Get the authenticated user
        $user = Auth::guard('api')->user();
        // If the authorized auth_id is not equal to the authId sent in request param
        return !empty($user) && $user->auth_id != $this->authId;
    }

    /**
     * Retrieve user for authentication
     */
    public function retrieveUserAndAuthenticate(): array
    {
        // Set the auth id of the user for authentication
        if (self::verifyDuplicateAccountScenario())
            // Duplicate account scenario
            return Utility::getStructuredResponse(
                ApiCodes::AUTH_ID_MISMATCH,
                __('api_auth.auth_id_mismatch'),
                self::getUnauthenticatedUserData(false, true)
            );

        // Retrieve User Auth Query builder
        $userAuthQueryBuilder = $this->userService->retrieveUserAuthByAuthId($this->authId);

        // Count the number of user
        $this->userCount = $userAuthQueryBuilder->count();

        // If user has more than one account
        if ($this->userCount > 1)
            // Duplicate user found
            return Utility::getStructuredResponse(
                ApiCodes::DUPLICATE_USER_FOUND,
                __('api_auth.multiple_accounts_found'),
                self::getUnauthenticatedUserData(false, true)
            );

        // As we create new user for auth type mobile
        if ($this->authType != UserAuthType::Mobile) {
            if (empty($this->userCount))
                // New User
                return Utility::getStructuredResponse(
                    ApiCodes::SOMETHING_WENT_WRONG,
                    __('api_auth.something_went_wrong'),
                    self::getUnauthenticatedUserData(true, false)
                );
        }

        // Retrieve User and authenticate
        return array_merge(
            self::getTypeUserData(false, false),
            AuthenticationType::instantiate(ClientType::User, $this->retrieveUser($userAuthQueryBuilder))
                ->signIn()
                ->respondAfterAuthentication()
        );
    }

    /**
     * Get unauthenticated user data
     *
     * @param bool $newUser
     * @param bool $duplicateUser
     * @return array
     */
    protected function getUnauthenticatedUserData(bool $newUser, bool $duplicateUser): array
    {
        return array_merge(self::getTypeUserData($newUser, $duplicateUser), [
            'auth_id' => $this->authId,
            'authType' => $this->authType
        ]);
    }

    /**
     * Get user type data
     *
     * @param bool $newUser
     * @param bool $duplicateUser
     * @return array
     */
    protected function getTypeUserData(bool $newUser, bool $duplicateUser): array
    {
        return [
            'newUser' => $newUser,
            'duplicateUser' => $duplicateUser
        ];
    }

    /**
     * Retrieve User
     *
     * @param Builder $userAuthQueryBuilder
     * @return UserAuth
     */
    protected function retrieveUser(Builder $userAuthQueryBuilder): UserAuth
    {
        return $userAuthQueryBuilder->first();
    }
}
