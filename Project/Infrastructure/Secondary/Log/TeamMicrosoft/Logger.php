<?php

namespace Project\Infrastructure\Secondary\Log\TeamMicrosoft;

use Monolog\Logger as MonologLogger;

class Logger extends MonologLogger
{
    /**
     * @param $url
     * @param int $level
     */
    public function __construct($url, $level = MonologLogger::DEBUG)
    {
        parent::__construct('microsoft-teams-logger');
        $handler = new LoggerHandler($url, $level);
        $handler->setFormatter(new Formatter());
        $this->pushHandler($handler);
    }
}
