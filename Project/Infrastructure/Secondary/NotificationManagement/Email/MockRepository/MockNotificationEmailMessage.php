<?php

namespace Project\Infrastructure\Secondary\NotificationManagement\Email\MockRepository;

use Project\Domain\NotificationManagement\Email\Enums\EmailStatus;
use Project\Domain\NotificationManagement\Email\Interfaces\NotificationEmailMessageRepositoryInterface;
use Project\Domain\NotificationManagement\Email\Models\NotificationEmailMessage as DomainNotificationEmailMessage;
use Project\Domain\NotificationManagement\Notification\Interfaces\NotificationRepositoryInterface;
use Project\Domain\NotificationManagement\Notification\Models\Notification;
use Project\Domain\UserManagement\User\Enums\UserStatus;
use Project\Domain\UserManagement\User\Models\User;
use Project\Infrastructure\Secondary\Database\Base\EloquentBaseRepository;
use Project\Infrastructure\Secondary\Database\NotificationManagement\Email\ModelDao\NotificationEmailMessage as ModelDAO;
use Project\Infrastructure\Secondary\Database\UserManagement\User\Repository\UserRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Tests\Feature\Command\Sms\SendSmsForUnreadEmailUsers\SendSmsForUnreadEmailUsersTest;

class MockNotificationEmailMessage extends EloquentBaseRepository implements NotificationEmailMessageRepositoryInterface
{
    public User $user;
    public Notification $notification;
    public DomainNotificationEmailMessage $notificationEmailMessage;
    public SendSmsForUnreadEmailUsersTest $tester;
    public UserRepository $userRepository;
    public NotificationRepositoryInterface $notificationRepository;

    public function __construct(
        ModelDAO $modelDao,
        UserRepository $userRepository,
        NotificationRepositoryInterface $notificationRepository
    ) {
        $this->userRepository = $userRepository;
        $this->notificationRepository = $notificationRepository;
        parent::__construct($modelDao);
    }

    public function getById(int $id): ?DomainNotificationEmailMessage
    {
        $modelDao = $this->createQuery()->where('id', $id)->first();

        return $modelDao ? $modelDao->toDomainEntity() : null;
    }

    public function getByKey(string $key): ?DomainNotificationEmailMessage
    {
        return null;
    }

    public function getUnread(): Collection
    {
        return new Collection();
    }

    public function getUserLastReadAtByNotificationEmailId(int $notificationEmailId): ?DomainNotificationEmailMessage
    {
        return null;
    }

    public function markAsRead(int $id): ?DomainNotificationEmailMessage
    {
        return null;
    }

    public function send(DomainNotificationEmailMessage $emailNotification): DomainNotificationEmailMessage
    {
        return $emailNotification;
    }


    public function save(DomainNotificationEmailMessage $emailNotification): DomainNotificationEmailMessage
    {
        return $this->createModelDAO($emailNotification->getId())
            ->saveData($emailNotification);
    }

    public function delete(DomainNotificationEmailMessage $emailNotification): bool
    {
        return true;
    }

    public function getLastInKeysByUserId(int $userId, array $keys): ?DomainNotificationEmailMessage
    {
        return null;
    }

    public function getUnreadEmailsByConditions(array $conditions): Collection
    {
        return new Collection();
    }

    public function updateIsSmsSentByIds(array $ids): void
    {
        $this->createQuery()->whereIn('id', $ids)->update(['is_sms_sent' => true]);
    }

    public function getUnsentSmsEmails(): Collection
    {

        $query = $this->createQuery()->select('notification_email_messages.*')
            ->with(['user', 'notification'])
            ->join('notifications', 'notifications.id', '=', 'notification_email_messages.notification_id')
            ->whereNotNull('notification_id')
            ->where('is_sms_sent', 0)
            ->where('notification_email_messages.status', EmailStatus::Success)
            ->whereNull('read_at')
            ->where('notifications.follow_interval', '>', 0)
            ->whereHas('user', function (Builder $query) {
                $query->whereNotIn('status', [
                    UserStatus::DeactivatedUser,
                    UserStatus::CancelledUser
                ]);
            })
            ->addSelect(DB::raw(
                "
                    (ROUND
                        (
                            (
                               JULIANDAY('" . Carbon::now() . "') - JULIANDAY(notification_email_messages.created_at)
                            ) * 86400
                        )
                        - notifications.follow_interval
                    ) AS diffTime
                "
            ))
            ->where('diffTime', '>=', 0)
            ->where('diffTime', '<=', 3600)
            ->orderBy('notification_email_messages.id')
            ->limit(1000);

        return $this->transformCollection($query->get());
    }

    public function getLatestInKeysByUserId(int $userId, array $keys): ?DomainNotificationEmailMessage
    {
        return null;
    }

    /**
     * @param int $notificationId
     * @param Carbon $datetime
     * @return Collection
     */
    public function getByNotificationIdAndDayAndTime(int $notificationId, Carbon $datetime): Collection
    {
        return new Collection();
    }
}
