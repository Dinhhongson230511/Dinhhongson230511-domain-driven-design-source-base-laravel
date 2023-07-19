<?php

namespace Project\Domain\NotificationManagement\Sms\Interfaces;

use Project\Domain\NotificationManagement\Sms\Models\NotificationSmsMessage;
use Carbon\Carbon;
use Illuminate\Support\Collection;

interface NotificationSmsMessageRepositoryInterface
{
    /**
     * @param int $id
     * @return NotificationSmsMessage|null
     */
    public function getById(int $id): ?NotificationSmsMessage;

    /**
     * @param string $key
     * @return NotificationSmsMessage|null
     */
    public function getByKey(string $key): ?NotificationSmsMessage;

    /**
     * @param NotificationSmsMessage $smsNotification
     * @return NotificationSmsMessage
     */
    public function save(NotificationSmsMessage $smsNotification): NotificationSmsMessage;

    /**
     * @param NotificationSmsMessage $smsNotification
     * @return bool
     */
    public function delete(NotificationSmsMessage $smsNotification): bool;

    /**
     * @param array $notificationSmsMessages
     */
    public function saveMultiRecord(array $notificationSmsMessages): void;

    /**
     * @param int $status
     * @return Collection
     */
    public function getNotificationSmsMessages(int $status): Collection;

    /**
     * @param string $key
     * @return ?Collection
     */
    public function getNotificationSmsMessagesByKeyAndCreatedToday(string $key): ?Collection;

    /**
     * @param int $notificationId
     * @param Carbon $datetime
     * @return Collection
     */
    public function getByNotificationIdAndDayAndTime(int $notificationId, Carbon $datetime): Collection;
}
