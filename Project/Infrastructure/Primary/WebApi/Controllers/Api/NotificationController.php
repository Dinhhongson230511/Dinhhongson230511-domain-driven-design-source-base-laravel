<?php

namespace Project\Infrastructure\Primary\WebApi\Controllers\Api;

use App\Http\Requests\MarkAsReadRequest;
use App\Http\Requests\RetrieveUserPushNotificationHistory;
use App\Http\Requests\SendNotificationRequest;
use Project\Application\User\Services\NotificationService;
use Project\Infrastructure\Primary\WebApi\Controllers\BaseController;
use Project\Infrastructure\Primary\WebApi\ResponseHandler\Api\ApiResponseHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends BaseController
{
    /**
     * @param MarkAsReadRequest $request
     * @param NotificationService $notificationService
     * @return JsonResponse
     *
     * @bodyParam mobileNumber string required The mobile number of the user who wants to migrate the account. Example: 09272663636
     * @response 302 redirect to social-login for re-authentication
     * @response 512 {
     *      "message":"Error Encountered while migrating account in \/Project\/Infrastructure\/Primary\/WebApi\/Controllers\/Api\/MigrationController.php at 52 due to `Exception message`",
     *      "data":[]
     *  }
     */
    public function markEmailNotificationAsRead(MarkAsReadRequest $request, NotificationService $notificationService)
    {
        $response = $notificationService->markEmailNotificationAsRead($request->get('code'));

        self::setResponse($response['status'], $response['message'], $response['data']);

        return ApiResponseHandler::jsonResponse($this->status, $this->message, $this->data);
    }

    /**
     * @param RetrieveUserPushNotificationHistory $request
     * @param NotificationService $notificationService
     * @return JsonResponse
     */
    public function pushNotificationHistory(RetrieveUserPushNotificationHistory $request, NotificationService $notificationService): JsonResponse
    {

        $user = Auth::user()->getDomainEntity();
        $response = $notificationService->getPushMessageHistory($user, $request->all())->handleApiResponse();
        $this->setResponse($response['status'], $response['message'], $response['data']);

        return ApiResponseHandler::jsonResponse($this->status, $this->message, $this->data);
    }

    /**
     * @param MarkAsReadRequest $request
     * @param NotificationService $notificationService
     * @return JsonResponse
     *
     * @bodyParam mobileNumber string required The mobile number of the user who wants to migrate the account. Example: 09272663636
     * @response 302 redirect to social-login for re-authentication
     * @response 512 {
     *      "message":"Error Encountered while migrating account in \/Project\/Infrastructure\/Primary\/WebApi\/Controllers\/Api\/MigrationController.php at 52 due to `Exception message`",
     *      "data":[]
     *  }
     */
    public function markPushNotificationAsRead(MarkAsReadRequest $request, NotificationService $notificationService): JsonResponse
    {
        $user = Auth::user()->getDomainEntity();
        $response = $notificationService->markPushNotificationAsRead($user, $request->get('code'))->handleApiResponse();
        self::setResponse($response['status'], $response['message'], $response['data']);

        return ApiResponseHandler::jsonResponse($this->status, $this->message, $this->data);
    }

    /**
     * @param SendNotificationRequest $request
     * @param NotificationService $notificationService
     * @return JsonResponse
     */
    public function sendAndPushNotificationByKey(SendNotificationRequest $request, NotificationService $notificationService): JsonResponse
    {
        $response = $notificationService->sendAndPushNotificationByKey($request->all());

        self::setResponse($response['status'], $response['message'], $response['data']);

        return ApiResponseHandler::jsonResponse($this->status, $this->message, $this->data);
    }
}
