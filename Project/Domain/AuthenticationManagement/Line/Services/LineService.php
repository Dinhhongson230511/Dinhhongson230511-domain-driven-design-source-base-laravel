<?php

namespace Project\Domain\AuthenticationManagement\Line\Services;

use Project\Domain\AuthenticationManagement\Authentication\Enums\UserAuthType;
use Project\Infrastructure\Secondary\AuthenticationManagement\Line\Interfaces\LineApiInterface;
use Illuminate\Contracts\Container\BindingResolutionException;

class LineService
{
    /*
     * @var LineServiceInterface
     */
    private $lineService;

    /**
     * LineRepository constructor.
     * @throws BindingResolutionException
     */
    public function __construct ()
    {
        // Instantiate line service
        $this->lineService = app()->make(LineApiInterface::class);
    }

    /**
     * Redirect to Line provider
     *
     * @return mixed
     */
    public function redirectToLineProvider (): string
    {
        // Get redirect url if any
        $redirectUrl = request('redirectUrl');

        if(!empty($redirectUrl) &&
            (      $redirectUrl == config('constants.new_line_login')
                || $redirectUrl == config('constants.app_line_login')
                || $redirectUrl == config("constants.add_line_to_facebook")
            ))
            return $this->lineService->getLineWebLoginUrl();

        return $this->lineService->getLineWebLoginUrlWithAddBoot();
    }

    /**
     * Handle callback from line
     *
     * @param string $code
     * @return string
     */
    public function handleLineCallback ( string $code ): string
    {
        // Retrieve user profile from line
        $lineProfile = $this->lineService->accessToken($code)->profile();

        // If data from line is empty then redirect to line login
        if(empty($lineProfile) && !is_array($lineProfile))
            return route('auth-login-line');

        // Login via social login api
        return env('WEB_APP_LOGIN_URL')."?authId=".$lineProfile['userId']."&authType=".UserAuthType::Line."&newUser=false&duplicateUser=false";
    }

}
