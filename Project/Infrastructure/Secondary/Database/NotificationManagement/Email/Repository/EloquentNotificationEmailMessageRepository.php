<?php

namespace Project\Infrastructure\Secondary\Database\NotificationManagement\Email\Repository;

use Project\Domain\NotificationManagement\Email\Enums\EmailStatus;
use Project\Domain\NotificationManagement\Email\Interfaces\NotificationEmailMessageRepositoryInterface;
use Project\Domain\NotificationManagement\Email\Models\NotificationEmailMessage;
use Project\Domain\UserManagement\User\Enums\UserStatus;
use Project\Infrastructure\Secondary\Database\Base\EloquentBaseRepository;
use Project\Infrastructure\Secondary\Database\NotificationManagement\Email\ModelDao\NotificationEmailMessage as ModelDAO;
use Project\Infrastructure\Secondary\Database\NotificationManagement\Email\ModelDao\QueuedEmail;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EloquentNotificationEmailMessageRepository extends EloquentBaseRepository implements NotificationEmailMessageRepositoryInterface
{
    public function __construct(ModelDAO $modelDao)
    {
        parent::__construct($modelDao);
    }

    /**
     * @param int $id
     * @return NotificationEmailMessage|null
     */
    public function getById(int $id): ?NotificationEmailMessage
    {
        $modelDao = $this->createQuery()->where('id', $id)->first();

        return $modelDao ? $modelDao->toDomainEntity() : null;
    }

    /**
     * @param string $key
     * @return NotificationEmailMessage|null
     */
    public function getByKey(string $key): ?NotificationEmailMessage
    {
        $modelDao = $this->createQuery()->where('key', $key)->first();

        return $modelDao ? $modelDao->toDomainEntity() : null;
    }

    /**
     * @return Collection
     */
    public function getUnread(): Collection
    {
        $q = $this->createQuery()->whereNull('read_at');

        return $this->transformCollection($q->get());
    }

    /**
     * @param int $notificationEmailId
     * @return NotificationEmailMessage|null
     */

    public function getUserLastReadAtByNotificationEmailId(int $notificationEmailId): ?NotificationEmailMessage
    {
        $notification = $this->createQuery()->select('user_id')
            ->from('notification_email_messages')
            ->where('id', $notificationEmailId)
            ->first();
        if (!$notification) {
            return null;
        }
        $userId = $notification->user_id;
        $modelDao = $this->createQuery()->where('user_id', $userId)
            ->whereNotNull('read_at')
            ->orderBy('read_at', 'DESC')
            ->first();

        return $modelDao ? $modelDao->toDomainEntity() : null;
    }

    /**
     * @param int $id
     * @return NotificationEmailMessage|null
     */
    public function markAsRead(int $id): ?NotificationEmailMessage
    {
        $emailNotification = $this->getById($id);

        if ($emailNotification) {
            $emailNotification->setReadAt(Carbon::now());

            return $this->save($emailNotification);
        }
        return null;
    }

    /**
     * @param NotificationEmailMessage $emailNotification
     * @return NotificationEmailMessage
     */
    public function send(NotificationEmailMessage $emailNotification): NotificationEmailMessage
    {
        $email = $emailNotification->getUser()->getEmail();
        if (!in_array($email, config('whitelist_in_dev')['email']) && env('APP_ENV') != 'production') {
            throw new \Exception($email . 'email is not verified!!');
        }

        $queuedEmail = (new QueuedEmail($emailNotification))
            ->replyTo(env('MAIL_REPLY_TO'), env('MAIL_FROM_NAME'));

        Mail::to($email)->send($queuedEmail);

        return $emailNotification;
    }

    /**
     * @return Collection
     */
    public function getUnsentSmsEmails(): Collection
    {
        /*
         * We only care the record which have diff in second from created_at to now greater than follow_interval.
         * This way reduce the amount of record in query
        */
        $q = $this->createQuery()->select('notification_email_messages.*')
            ->with(['user', 'notification'])
            ->join('notifications','notifications.id', '=','notification_email_messages.notification_id')
            ->addSelect(DB::raw(
                "(
                    CAST(
                        TIMESTAMPDIFF(
                            SECOND,
                            notification_email_messages.created_at,
                            '" . Carbon::now() . "'
                        ) AS  DECIMAL ( 35, 2 )
                    ) - CAST(
                        notifications.follow_interval as DECIMAL ( 35, 2 )
                    )
                )  AS diffTime"
            ))
            ->whereNotNull('notification_id')
            ->where('is_sms_sent', 0)
            ->where('notification_email_messages.status', EmailStatus::Success)
            ->whereNull('read_at')
            ->where('notifications.follow_interval', '>',0)
            ->whereHas('user', function (Builder $query) {
                $query->whereNotIn('status', [
                    UserStatus::DeactivatedUser,
                    UserStatus::CancelledUser
                ]);
            })
            ->havingBetween('diffTime',[0,3600])
            ->orderBy('notification_email_messages.id')
            ->limit(1000);

        return $this->transformCollection($q->get());
    }

    /**
     * @param array $ids
     */
    public function updateIsSmsSentByIds(array $ids): void
    {
        $this->createQuery()->whereIn('id', $ids)->update(['is_sms_sent' => true]);
    }

    /**
     * @param NotificationEmailMessage $emailNotification
     * @return NotificationEmailMessage
     */
    public function save(NotificationEmailMessage $emailNotification): NotificationEmailMessage
    {
        return $this->createModelDAO($emailNotification->getId())
            ->saveData($emailNotification);
    }

    /**
     * @param NotificationEmailMessage $emailNotification
     * @return bool
     */
    public function delete(NotificationEmailMessage $emailNotification): bool
    {
        return $this->deleteById($emailNotification->getId());
    }

    /**
     * @param int $userId
     * @param array $keys
     * @return NotificationEmailMessage|null
     */
    public function getLastInKeysByUserId(int $userId, array $keys): ?NotificationEmailMessage
    {
        $modelDao = $this->createQuery()
            ->where('status', EmailStatus::Success)
            ->whereIn('key', $keys)
            ->orderBy('id', 'desc')
            ->first();

        return $modelDao ? $modelDao->toDomainEntity() : null;
    }

    /**
     * @param array $conditions
     * @return Collection
     */
    public function getUnreadEmailsByConditions(array $conditions): Collection
    {
        $query = $this->createQuery()
            ->with(['user', 'notification'])
            ->whereNotNull('notification_id')
            ->whereNull('read_at')
            ->where($conditions);

        return $this->transformCollection($query->get());
    }

    /**
     * @param int $userId
     * @param array $keys
     * @return NotificationEmailMessage|null
     */
    public function getLatestInKeysByUserId(int $userId, array $keys) : ?NotificationEmailMessage
    {
        $modelDao = $this->createQuery()
            ->where('user_id', $userId)
            ->where('status', EmailStatus::Success)
            ->whereIn('key', $keys)
            ->orderBy('id', 'desc')
            ->first();

        return $modelDao ? $modelDao->toDomainEntity() : null;
    }

    /**
     * @param int $notificationId
     * @param Carbon $datetime
     * @return Collection
     */
    public function getByNotificationIdAndDayAndTime(int $notificationId, Carbon $datetime): Collection
    {
        return $this->transformCollection(
            $this->createQuery()
                ->where('notification_id', $notificationId)
                ->whereDate('created_at', $datetime->toDateString())
                ->whereTime('created_at', '<=', $datetime->toTimeString())
                ->get()
        );
    }
}
