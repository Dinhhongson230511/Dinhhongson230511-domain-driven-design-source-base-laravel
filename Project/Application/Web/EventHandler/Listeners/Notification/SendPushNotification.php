<?php

namespace Project\Application\Web\EventHandler\Listeners\Notification;

use Project\Domain\NotificationManagement\Email\Events\SendEmailToUser;
use Project\Domain\NotificationManagement\Notification\Enums\NotificationType;
use Project\Domain\NotificationManagement\Notification\Interfaces\NotificationRepositoryInterface;
use Project\Domain\NotificationManagement\Push\Services\PushNotificationDomainService;

class SendPushNotification
{
    private PushNotificationDomainService $pushNotificationDomainService;
    private NotificationRepositoryInterface $notificationRepository;

    /**
     * @param PushNotificationDomainService $pushNotificationDomainService
     * @param NotificationRepositoryInterface $notificationRepository
     */
    public function __construct(
        PushNotificationDomainService $pushNotificationDomainService,
        NotificationRepositoryInterface $notificationRepository
    ) {
        $this->pushNotificationDomainService = $pushNotificationDomainService;
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * @param SendEmailToUser $event
     */
    public function handle(SendEmailToUser $event)
    {
        $pushNotifications = $this->notificationRepository->getByKey($event->getNotification()->getKey(), NotificationType::Push);
        if ($pushNotifications) {
            $pushNotifications->mapVariables($event->getNotification()->getMappedVariables());
            $this->pushNotificationDomainService->sendPushNotificationToUser($event->getUser(), $pushNotifications);
        }
    }
}
