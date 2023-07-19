<?php


namespace Project\Infrastructure\Secondary\Log\TeamMicrosoft;


use Monolog\Formatter\HtmlFormatter;
use Monolog\Utils;

class Formatter extends HtmlFormatter
{

    public function format(array $record): string
    {
        if (url()->full())
        {
            $record['context']['route'] = url()->full();
        }

        return parent::format($record);
    }

    protected function normalizeException(\Throwable $e, int $depth = 0)
    {
        if ($e instanceof \JsonSerializable) {
            return (array) $e->jsonSerialize();
        }

        $data = [
            'class' => Utils::getClass($e),
            'message' => $e->getMessage(),
            'code' => (int) $e->getCode(),
            'file' => $e->getFile().':'.$e->getLine(),
        ];

        if ($e instanceof \SoapFault) {
            if (isset($e->faultcode)) {
                $data['faultcode'] = $e->faultcode;
            }

            if (isset($e->faultactor)) {
                $data['faultactor'] = $e->faultactor;
            }

            if (isset($e->detail)) {
                if (is_string($e->detail)) {
                    $data['detail'] = $e->detail;
                } elseif (is_object($e->detail) || is_array($e->detail)) {
                    $data['detail'] = $this->toJson($e->detail, true);
                }
            }
        }

        $trace = $e->getTrace();
        foreach ($trace as $frame) {
            if (isset($frame['file'])) {
                $data['trace'][] = $frame['file'].':'.$frame['line'];
            }
        }

        //Note: we do not log the previuos exception here to make to message short enough for Team channel

        return $data;
    }
}
