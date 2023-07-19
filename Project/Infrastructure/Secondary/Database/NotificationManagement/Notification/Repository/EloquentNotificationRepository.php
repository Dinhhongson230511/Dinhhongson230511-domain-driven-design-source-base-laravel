<?php

namespace Project\Infrastructure\Secondary\Database\NotificationManagement\Notification\Repository;

use Project\Domain\NotificationManagement\Notification\Enums\NotificationType;
use Project\Domain\NotificationManagement\Notification\Interfaces\NotificationRepositoryInterface;
use Project\Domain\NotificationManagement\Notification\Models\Notification;
use Project\Infrastructure\Secondary\Database\Base\EloquentBaseRepository;
use Project\Infrastructure\Secondary\Database\NotificationManagement\Notification\ModelDao\Notification as ModelDAO;
use Illuminate\Support\Collection;

class EloquentNotificationRepository extends EloquentBaseRepository implements NotificationRepositoryInterface
{
    /**
     * @param ModelDAO $modelDao
     */
    public function __construct(ModelDAO $modelDao)
    {
        parent::__construct($modelDao);
    }

    /**
     * @param int $id
     * @return Notification|null
     */
    public function getById(int $id): ?Notification
    {
        $modelDao = $this->createQuery()->where('id', $id)->first();

        return $modelDao ? $modelDao->toDomainEntity() : null;
    }

    /**
     * @param string $key
     * @param string $type
     * @return Notification|null
     */
    public function getByKey(string $key, string $type = NotificationType::Email): ?Notification
    {
        $modelDao = $this->createQuery()
            ->where('key', $key)
            ->where('type', $type)
            ->first();

        return $modelDao ? $modelDao->toDomainEntity() : null;
    }

    /**
     * @param array $keys
     * @param string $type
     * @return Collection
     */
    public function getByKeys(array $keys, string $type = NotificationType::Email): Collection
    {
        $notifications = $this->createQuery()
            ->whereIn('key', $keys)
            ->where('type', $type)
            ->get();

        return $this->transformCollection($notifications);
    }

    /**
     * @param string $eligibleUserKey
     * @return Notification|null
     */
    public function getByEligibleUserKey(string $eligibleUserKey): ?Notification
    {
        $modelDao = $this->createQuery()->where('eligible_user_key', $eligibleUserKey)->first();

        return $modelDao ? $modelDao->toDomainEntity() : null;
    }

    /**
     * Retrieve users by filter
     * @param array $filter
     * @return array
     */
    public function getPaginateArray($filter = []): array
    {
        return $this->modelDAO->buildIndexQuery(['filter' => $filter])->simplePaginate()->toArray();
    }

    /**
     * @param string $type
     * @param string|null $search
     * @return Collection
     */
    public function getByConditions(string $type, ?string $search = ''): Collection
    {
        $query = $this->createQuery()->where('type', $type);

        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                return $query->where('key', 'like', '%' . $search . '%')
                    ->orWhere('eligible_user_key', 'like', '%' . $search . '%')
                    ->orWhere('label', 'like', '%' . $search . '%')
                    ->orWhere('title', 'like', '%' . $search . '%')
                    ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        return $this->transformCollection($query->get());
    }

    /**
     * @param Notification $notification
     * @return Notification
     */
    public function save(Notification $notification): Notification
    {
        return $this->createModelDAO($notification->getId())
            ->saveData($notification);
    }
}
