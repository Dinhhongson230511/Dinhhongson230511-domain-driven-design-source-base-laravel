<?php

namespace Project\Infrastructure\Primary\Web\Traits;

use Project\UserInterface\Helpers\Log;
use Exception;
use Illuminate\Support\Facades\App;

trait HandleResponse
{
    /**
     * Set response for the controller
     *
     * @param int $status
     * @param string $message
     * @param array $data
     */
    public function setResponse(int $status, string $message, array $data)
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * Catch Errors
     *
     * @param Exception $exception
     * @param string $message
     * @param string $method
     */
    protected function catchError(\Exception $exception, string $message, string $method) : void
    {
        Log::error('Error Encountered while '.$message.' in '.$exception->getFile().' at '.$exception->getLine().' due to '.$exception->getMessage() , [
                'exception' => json_encode([
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'message' => $exception->getMessage()
                ]),
                'data' => json_encode(request()->all()),
                'method' => $method
            ]
        );
        $this->setResponse(
            $this->status, 
            App::environment('production') ? __('api_auth.something_went_wrong')
                : 'Error Encountered while '.$message.' in '.$exception->getFile().' at '.$exception->getLine().' due to '.$exception->getMessage()
                ?? __('api_auth.something_went_wrong'), 
            $this->data
        );
    }


    /**
     * Format Registration data
     *
     * @return array
     */
    public function handleApiResponse(): array
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data
        ];
    }
}
