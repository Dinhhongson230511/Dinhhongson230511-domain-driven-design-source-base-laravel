<?php

namespace Project\Infrastructure\Primary\Web\Controllers;

use App\Http\Controllers\Controller;
use Project\Application\User\Services\UserService;
use Project\Domain\NotificationManagement\Notification\Models\Notification;
use Project\Domain\NotificationManagement\Notification\Interfaces\NotificationRepositoryInterface;
use Project\UserInterface\Helpers\Log;
use Project\Domain\UserManagement\User\Models\User;
use Project\Domain\UserManagement\User\Interfaces\UserRepositoryInterface;
use Project\Domain\NotificationManagement\Notification\Services\NotificationService;

/*
 * Class UserController
 * @package Project\Infrastructure\Primary\WebApi\Controllers\Api
 *
 * @group User
 */

class TestController extends Controller
{
    /**
     * @var NotificationRepositoryInterface
     */
    protected NotificationRepositoryInterface $notificationRepository;

    private UserRepositoryInterface $userRepository;

    /**
     * @var NotificationService
     */
    protected NotificationService $notificationService;


    public function __construct(
        NotificationRepositoryInterface $notificationRepository,
        UserRepositoryInterface $userRepository,
        NotificationService $notificationService

    ) {
        $this->notificationRepository = $notificationRepository;
        $this->userRepository = $userRepository;
        $this->notificationService = $notificationService;
    }

    public function index(UserService $userService)
    {

        $user = $this->userRepository->getById(1);
        $notification = $this->getNotification();
        if (!$notification) return;
        
        $this->notificationService->sendEmailNotificationToUser($user, $notification);
        $this->data = $userService->index();

        
    }

    private function getNotification(): ?Notification
    {
        $key = config('notification_keys.complete_registation');
        $notification = $this->notificationRepository->getByKey($key);

        if (!$notification) {
            Log::info('Notification is not found.');
            return null;
        }
        // $notification->mapVariable('new_plan', $newPlan->getCostPlan()->getName());
        $notification->mapVariable('contract_term', 'test');

        return $notification;
    }
}
