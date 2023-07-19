<?php

namespace Project\Application\User\Services;

use Project\Application\User\Services\Interfaces\NotificationServiceInterface;
use Project\Domain\NotificationManagement\Email\Interfaces\NotificationEmailMessageRepositoryInterface;
use Project\UserInterface\Helpers\Utility;
use Illuminate\Http\Response;
use Project\Domain\NotificationManagement\Notification\Services\NotificationService as NotificationDomainService;

class NotificationService implements NotificationServiceInterface
{
    /**
     * @var int
     */
    private $status;

    /**
     * @var string
     */
    private $message;

    /**
     * @var array
     */
    private $data = [];

    private NotificationDomainService $notificationDomainService;


    /**
     * @var NotificationEmailMessageRepositoryInterface
     */
    private NotificationEmailMessageRepositoryInterface $notificationEmailMessageRepository;

    public function __construct(
        NotificationEmailMessageRepositoryInterface $notificationEmailMessageRepository,
        NotificationDomainService                   $notificationDomainService,
    ) {
        $this->notificationDomainService = $notificationDomainService;
        $this->notificationEmailMessageRepository = $notificationEmailMessageRepository;
        $this->status = Response::HTTP_OK;
        $this->message = __('api_messages.successful');
    }

    /**
     * @param string $id
     * @return array
     */
    public function markEmailNotificationAsRead(string $id): array
    {
        $notificationEmailId = (int) Utility::decode($id);
        $checkPreviousNotificationRead = $this->notificationDomainService->pastSecAfterLastRead($notificationEmailId);
        if ($checkPreviousNotificationRead) {
            $this->notificationEmailMessageRepository->markAsRead($notificationEmailId);
        }

        return $this->handleApiResponse();
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
