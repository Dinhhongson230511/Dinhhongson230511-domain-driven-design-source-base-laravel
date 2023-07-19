<?php

namespace Project\UserInterface\Helpers;

use Illuminate\Support\Facades\Log as LogFacade;

class Log extends LogFacade
{
    /**
     * Handle information log
     *
     * @param string $message
     * @param array $context
     */
    public static function info ( string $message = '' , array $context = [])
    {
        parent::info($message , $context);
    }

    /**
     * Handle error logs
     *
     * @param string $message
     * @param array $context
     * @param string $event
     */
    public static function error ( string $message = '' , array $context = [])
    {
        parent::error($message , $context);
    }

}
