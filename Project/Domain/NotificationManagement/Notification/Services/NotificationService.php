<?php

namespace Project\Domain\NotificationManagement\Notification\Services;

use Project\Domain\Base\BaseDomainModel;
use Project\Domain\NotificationManagement\Email\Enums\EmailStatus;
use Project\Domain\NotificationManagement\Email\Events\SendEmailToUser;
use Project\Domain\NotificationManagement\Email\Interfaces\NotificationEmailMessageRepositoryInterface;
use Project\Domain\NotificationManagement\Email\Models\NotificationEmailMessage;
use Project\Domain\NotificationManagement\Notification\Enums\NotificationStatus;
use Project\Domain\NotificationManagement\Notification\Models\Notification;
use Project\Domain\UserManagement\User\Enums\UserStatus;
use Project\Domain\UserManagement\User\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Project\Domain\NotificationManagement\Notification\Enums\NotificationType;

class NotificationService
{
    private NotificationEmailMessageRepositoryInterface $notificationEmailMessageRepository;

    public function __construct(
        NotificationEmailMessageRepositoryInterface $notificationEmailMessageRepository,
    ) {
        $this->notificationEmailMessageRepository = $notificationEmailMessageRepository;
    }

    /**
     * @param User $user
     * @param Notification $notification
     */
    public function sendEmailNotificationToUser(User $user, Notification $notification, bool $allowCancelledOrDeactivated = false): void
    {
        try {
            // process to send email
            if (is_null($user->getEmail())) return;
            // if ($user->getDiscardedAt()) return;
            if (is_null($notification->getContent())) return;
            if ($notification->getStatus() == NotificationStatus::Inactive) return;
            // if (!empty($notification->getPrefectureIds()) && !in_array($user->getPrefectureId(), $notification->getPrefectureIds())) return;
            // if (!$allowCancelledOrDeactivated && in_array(
            //     $user->getStatus(),
            //     [UserStatus::DeactivatedUser, UserStatus::CancelledUser]
            // )) {
            //     return;
            // }

            // if ($this->isUserNotificationExclusion($notification->getKey(), $user->getId(), NotificationType::Email)) {
            //     return;
            // }

            $notificationEmailMessage = new NotificationEmailMessage(
                $user->getId(),
                $notification->getKey(),
                $notification->generateTitle(),
                $notification->generateContent(),
                EmailStatus::Processing,
                $notification->getId()
            );
            $this->notificationEmailMessageRepository->save($notificationEmailMessage)->setUser($user);
            $this->notificationEmailMessageRepository->send($notificationEmailMessage)->setStatus(EmailStatus::Success);
            $this->notificationEmailMessageRepository->save($notificationEmailMessage);
            // dispatch to send push notification
            // SendEmailToUser::dispatch($user, $notification);
        } catch (\Throwable $th) {
            Log::error($th, [
                'user_id' => $user->getId(),
                'notification_id' => $notification->getId(),
            ]);
            return;
        }
    }

    /**
     * @param string $key
     * @param int $userId
     * @return bool
     */
    public function isUserNotificationExclusion(string $key, int $userId, string $type): bool
    {
        $userNotificationExclusion = $this->userNotificationExclusionRepository->getByKeyAndUser(
            $key,
            $userId
        );
        if (
            !empty($userNotificationExclusion)
            && $userNotificationExclusion->status == NotificationStatus::Active
            && $userNotificationExclusion->type == $type
        ) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param NotificationEmailMessage $notificationEmailMessage
     * @param Notification|null $smsNotification
     * @param Notification|null $secondPushNotification
     * @return BaseDomainModel|null
     */
    public function prepareNotificationSmSOrPushToSend(
        User $user,
        NotificationEmailMessage $notificationEmailMessage,
        ?Notification $smsNotification,
        ?Notification $secondPushNotification
    ): ?BaseDomainModel {
        if (
            !$smsNotification
            || $smsNotification->getStatus() == NotificationStatus::Inactive
            || is_null($notificationEmailMessage->getContent())
            || (!empty($smsNotification->getPrefectureIds()) &&
                !in_array($notificationEmailMessage->getUser()->getPrefectureId(), $smsNotification->getPrefectureIds()))
        ) {
            return null;
        }

        // make push or sms notification message
        return $this->createNotificationToSend($user, $smsNotification, $secondPushNotification);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isValidToSendPushNotification(User $user): bool
    {
        if (!empty($user->getUserMetaData()) && !empty($user->getUserMetaData()->getOneSignalPlayerId())) {
            $deviceInfo = $this->extPushNotificationRepository->getDeviceByPlayerId($user->getUserMetaData()->getOneSignalPlayerId());
            if (isset($deviceInfo['invalid_identifier']) && !$deviceInfo['invalid_identifier']) {
                return true;
            }
            if (is_null($deviceInfo)) {
                Log::error('Can not get device info. UserId: ' . $user->getId());
            }
        }
        return false;
    }

    /**
     * @param int $notificationEmailId
     * @param float $sec
     * @return bool
     */
    public function pastSecAfterLastRead(int $notificationEmailId, float $sec = 1): bool
    {
        $previousNotificationRead = $this->notificationEmailMessageRepository->getUserLastReadAtByNotificationEmailId($notificationEmailId);

        if ($previousNotificationRead) {
            $totalDuration = Carbon::now()->diffInSeconds($previousNotificationRead->getReadAt()->toDateTimeString());

            if ($totalDuration <= $sec)
                return false;
        }

        return true;
    }
}
