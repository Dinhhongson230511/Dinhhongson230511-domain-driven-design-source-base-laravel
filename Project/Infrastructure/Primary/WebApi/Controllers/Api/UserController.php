<?php

namespace Project\Infrastructure\Primary\WebApi\Controllers\Api;

use Project\Infrastructure\Primary\WebApi\Controllers\BaseController;
use Project\Infrastructure\Primary\WebApi\ResponseHandler\Api\ApiResponseHandler;
use Project\Application\User\Services\UserService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Throwable;

/*
 * Class UserController
 * @package Project\Infrastructure\Primary\WebApi\Controllers\Api
 *
 * @group User
 */

class UserController extends BaseController
{

    public function index(UserService $userService)
    {
        $this->data = $userService->index();

        return ApiResponseHandler::jsonResponse(Response::HTTP_OK, __('api_messages.successful'), $this->data);
    }

    /**
     * Get current server time global
     *
     * @return JsonResponse
     *
     *
     * @response 200 {
     *    "message": "Successful",
     *    "data": []
     * }
     * @response 512 {
     *   "message": "error",
     *   "data": []
     * }
     */
    public function getCurrentServerTime()
    {
        try {
            $this->data['current_server_time'] = Carbon::now()->toDateTimeString();
        } catch (Exception $exception) {
            DB::rollback();
            throw $exception;
        }
        return ApiResponseHandler::jsonResponse(Response::HTTP_OK, __('api_messages.successful'), $this->data);
    }
}
