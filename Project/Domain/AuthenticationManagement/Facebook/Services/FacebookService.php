<?php

namespace Project\Domain\AuthenticationManagement\Facebook\Services;

use Project\Domain\UserManagement\User\Interfaces\UserRepositoryInterface;
use Project\Infrastructure\Secondary\AuthenticationManagement\Facebook\Interfaces\FacebookApiInterface;
use Project\Infrastructure\Secondary\Database\UserManagement\User\Interfaces\EloquentUserAuthInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use Symfony\Component\HttpFoundation\RedirectResponse;

class FacebookService
{
    /*
     * @var FacebookApiInterface
     */
    private $facebookApi;
    private UserRepositoryInterface $userRepository;
    private EloquentUserAuthInterface $userAuthRepository;

    /**
     * FacebookRepository constructor.
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->facebookApi = app()->make(FacebookApiInterface::class);
        $this->userRepository = app()->make(UserRepositoryInterface::class);
        $this->userAuthRepository = app()->make(EloquentUserAuthInterface::class);
    }

    /**
     * Handle redirection to facebook provider
     *
     * @return RedirectResponse
     */
    public function handleRedirectToFacebookProvider(): RedirectResponse
    {
        return $this->facebookApi->redirectToFacebookProvider();
    }

    /**
     * Handle facebook oauth callback
     *
     * @param array $params
     * @return string
     */
    public function handleOauthCallbackFromFacebook(array $params): string
    {
        $user = $this->facebookApi->handleFacebookCallback();
        if (empty($user)) {
            return route('auth-login-fb');
        } else {
            $userData = null;
            if (isset($user->email)) {
                $userData = $this->userRepository->getUserByEmail($user->email);
            } else {
                $userAuthData = $this->userAuthRepository->retrieveUserAuthViaAuthIdQueryBuilder($user->id)->get();
                if ($userAuthData->isNotEmpty()){
                    $userData = $userAuthData->user;
                }
            }
            if ($userData == null){
                return env('WEB_APP_LOGIN_URL') . "?authId=" . $user->token . "&authType=" . $params['authType'] . "&newUser=true&duplicateUser=false";
            }else{
                if ($userData->getMobileNumber() != null){
                    return env('WEB_APP_LOGIN_URL') . "?authId=" . $user->token . "&authType=" . $params['authType'] . "&newUser=false&duplicateUser=false";
                }else{
                    return env('WEB_APP_LOGIN_URL') . "?authId=" . $user->token . "&authType=" . $params['authType'] ."needs_mobile_number_verification=true" . "&newUser=false&duplicateUser=false";
                }
            }
        }
    }

}
