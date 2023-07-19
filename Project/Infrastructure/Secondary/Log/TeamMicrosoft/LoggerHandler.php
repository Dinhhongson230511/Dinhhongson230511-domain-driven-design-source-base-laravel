<?php

namespace Project\Infrastructure\Secondary\Log\TeamMicrosoft;

use Project\UserInterface\Helpers\Log;
use Monolog\Logger as MonologLogger;

;

use Monolog\Handler\AbstractProcessingHandler;

class LoggerHandler extends AbstractProcessingHandler
{
    /** @var string */
    private $url;

    /**
     * @param $url
     * @param int $level
     * @param bool $bubble
     */
    public function __construct($url, $level = MonologLogger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);

        $this->url = $url;
    }

    /**
     * @param array $record
     */
    protected function write(array $record): void
    {
        if (env('APP_ENV') !== 'production') {
            return;
        }

        if ($record['level'] <= MonologLogger::INFO) {
            return;
        }

        $text = $record['formatted'];

        $sizeOfException = strlen($text) / 1000;

        // 28KB is the limit size of post in channel of microsoft team
        $text = $sizeOfException >= 25000 ? mb_strcut($text, 0, 24999) : $text;

        $json = json_encode(new LoggerMessage([
            'text' => $text
        ]));

        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json)
        ]);

        $result = curl_exec($ch);

        if ($result != 1) {
            Log::channel('single')->error('Can not push message to Microsoft Team' . $result);
        }
    }
}
