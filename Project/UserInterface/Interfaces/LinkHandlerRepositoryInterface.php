<?php

namespace Project\UserInterface\Interfaces;

interface LinkHandlerRepositoryInterface
{
    /*
     * URL shortener
     * $link = new yedincisenol\DynamicLinks\DynamicLink('http://yeni.co/');
     * $shortLink = $dynamicLink->create($link, 'UNGUESSABLE');
     */
    public function shortenUrl(string $url) : ?string;
}
