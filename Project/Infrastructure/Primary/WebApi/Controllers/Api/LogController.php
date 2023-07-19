<?php


namespace Project\Infrastructure\Primary\WebApi\Controllers\Api;


use Project\Infrastructure\Primary\WebApi\Controllers\BaseController;
use Project\Infrastructure\Primary\WebApi\ResponseHandler\Api\ApiResponseHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class LogController extends BaseController
{
    public function __construct()
    {
        $this->status = Response::HTTP_OK;
        $this->message = $this->message = __('api_messages.successful');;
    }

    public function store(Request $request) {
        if (env('APP_ENV') == 'production') {
            Log::channel('teams')->error($request->get('error'));
        }
        Log::channel('frontend')->error($request->get('error'));

        return ApiResponseHandler::jsonResponse(200, __('api_messages.successful'));
    }
}
