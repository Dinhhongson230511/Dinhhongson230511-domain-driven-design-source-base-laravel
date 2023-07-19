<?php

namespace Project\Domain\NotificationManagement\Notification\Interfaces;

use Project\Domain\Base\Filter;
use Project\Domain\NotificationManagement\Notification\Enums\NotificationType;
use Project\Domain\NotificationManagement\Notification\Models\Notification;
use Illuminate\Support\Collection;

interface NotificationRepositoryInterface
{
    /**
     * @param int $id
     * @return Notification|null
     */
    public function getById(int $id): ?Notification;

    /**
     * @param string $key
     * @param string $type
     * @return Notification|null
     */
    public function getByKey(string $key, string $type = NotificationType::Email): ?Notification;

    /**
     * @param array $keys
     * @param string $type
     * @return Collection
     */
    public function getByKeys(array $keys, string $type = NotificationType::Email): Collection;

    /**
     * @param string $eligibleUserKey
     * @return Notification|null
     */
    public function getByEligibleUserKey(string $eligibleUserKey): ?Notification;

    /**
     * @param array $filter
     * @return array
     */
    public function getPaginateArray(array $filter = []): array;

    /**
     * @param string $type
     * @param string|null $search
     * @return Collection
     */
    public function getByConditions(string $type, ?string $search = ''): Collection;

    /**
     * @param Notification $notification
     * @return Notification
     */
    public function save(Notification $notification): Notification;
}
