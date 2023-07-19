<?php

namespace Project\Infrastructure\Secondary\Database\NotificationManagement\Sms\Repository;
use Project\Domain\NotificationManagement\Sms\Interfaces\NotificationSmsMessageRepositoryInterface;
use Project\Domain\NotificationManagement\Sms\Models\NotificationSmsMessage;
use Project\Infrastructure\Secondary\Database\Base\EloquentBaseRepository;
use Project\Infrastructure\Secondary\Database\NotificationManagement\Sms\ModelDao\NotificationSmsMessage as ModelDao;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class EloquentNotificationSmsMessageRepository extends EloquentBaseRepository implements NotificationSmsMessageRepositoryInterface
{
    /**
     * EloquentNotificationLogRepository constructor.
     * @param ModelDao $modelDao
     */
    public function __construct(ModelDao $modelDao)
    {
        parent::__construct($modelDao);
    }

    /**
     * @param int $id
     * @return NotificationSmsMessage|null
     */
    public function getById(int $id): ?NotificationSmsMessage
    {
        $modelDao = $this->createQuery()->where('id', $id)->first();

        return $modelDao ? $modelDao->toDomainEntity() : null;
    }

    /**
     * @param string $key
     * @return NotificationSmsMessage|null
     */
    public function getByKey(string $key): ?NotificationSmsMessage
    {
        $modelDao = $this->createQuery()->where('key', $key)->first();

        return $modelDao ? $modelDao->toDomainEntity() : null;
    }

    /**
     * @param NotificationSmsMessage $smsNotification
     * @return NotificationSmsMessage
     */
    public function save(NotificationSmsMessage $smsNotification): NotificationSmsMessage
    {
        return $this->createModelDAO($smsNotification->getId())
            ->saveData($smsNotification);
    }

    /**
     * @param NotificationSmsMessage $smsNotification
     * @return bool
     */
    public function delete(NotificationSmsMessage $smsNotification): bool
    {
        return $this->deleteById($smsNotification->getId());
    }

    /**
     * @param array $notificationSmsMessages
     */
    public function saveMultiRecord(array $notificationSmsMessages): void
    {
        $this->createQuery()->insert($notificationSmsMessages);
    }

    public function getNotificationSmsMessages(int $status): Collection
    {
        return $this->createQuery()
            ->with(['user'])
            ->where('status', $status)
            ->get();
    }

    public function getNotificationSmsMessagesByKeyAndCreatedToday(string $key): ?Collection
    {
        return $this->createQuery()
            ->where('key', $key)
            ->whereDay('created_at', Carbon::today()->day)
            ->get();
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
