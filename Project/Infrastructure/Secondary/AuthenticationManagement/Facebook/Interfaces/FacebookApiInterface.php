<?php

namespace Project\Infrastructure\Secondary\AuthenticationManagement\Facebook\Interfaces;

use Symfony\Component\HttpFoundation\RedirectResponse;

interface FacebookApiInterface
{
    /**
     * Redirect to facebook for authentication
     *
     * @return RedirectResponse
     */
    public function redirectToFacebookProvider() : RedirectResponse;

    /**
     * Handle facebook callback
     *
     * @return mixed
     */
    public function handleFacebookCallback() ;
}
