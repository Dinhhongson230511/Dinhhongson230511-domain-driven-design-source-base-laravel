<?php

namespace Project\Infrastructure\Secondary\AuthenticationManagement\Facebook\Repository;

use Project\Infrastructure\Secondary\AuthenticationManagement\Facebook\Interfaces\FacebookApiInterface;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class FacebookApi implements FacebookApiInterface
{

    /**
     * Redirect to facebook for authentication
     *
     * @return RedirectResponse
     */
    public function redirectToFacebookProvider (): RedirectResponse
    {
        // Redirect to facebook provider
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Handle facebook callback
     *
     * @return mixed
     */
    public function handleFacebookCallback ()
    {
        // Retrieve user data via socialite
        return Socialite::driver('facebook')->fields([ 'id', 'email', 'name', 'first_name', 'last_name', 'link', 'gender' ])
            ->scopes(['email', 'user_gender', 'link'])
            ->user();
    }

}
