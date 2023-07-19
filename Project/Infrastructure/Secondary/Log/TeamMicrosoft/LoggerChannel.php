<?php

namespace Project\Infrastructure\Secondary\Log\TeamMicrosoft;

use Monolog\Logger as MonologLogger;

class LoggerChannel
{
    /**
     * @param array $config
     *
     * @return Logger
     */
    public function __invoke(array $config)
    {
        return new Logger($config['url'], $config['level'] ?? MonologLogger::DEBUG);
    }
}
