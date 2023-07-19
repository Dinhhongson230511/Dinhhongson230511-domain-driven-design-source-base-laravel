<?php

namespace Project\Infrastructure\Secondary\Database\UserManagement\User\Repository;

use Project\Domain\Base\Filter;
use Project\Domain\UserManagement\User\Interfaces\UserRepositoryInterface;
use Project\Domain\UserManagement\User\Models\User as UserDomainModel;
use Project\Infrastructure\Secondary\Database\Base\EloquentBaseRepository;
use Project\Infrastructure\Secondary\Database\UserManagement\User\ModelDao\User as UserDao;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

/**
 * @TODO: implement UserRepositoryInterface instead
 */
class UserRepository extends EloquentBaseRepository implements UserRepositoryInterface
{

    /**
     * EloquentUserRepository constructor.
     * @param UserDao $model
     * @param UserInfoUpdatedTime $userInfoUpdatedTime
     */
    public function __construct(UserDao $model)
    {
        parent::__construct($model);
    }

    /**
     * @param int $id
     * @param array|null $relations
     * @return UserDomainModel|null
     */
    public function getById(int $id, ?array $relations = []): ?UserDomainModel
    {
        $query = $this->createQuery()->where('id', $id);
        if (!empty($relations)) {
            $query = $query->with($relations);
        }
        $modelDao = $query->first();

        return $modelDao ? $modelDao->toDomainEntity() : null;
    }

    /**
     * @param string $email
     * @return UserDomainModel|null
     */
    public function getUserByEmail(string $email): ?UserDomainModel
    {
        return optional($this->createQuery()->where('email', $email)->first())->toDomainEntity();
    }

    public function getByIdsRaw(array $ids, string $gender = null, int|array $userStatus = null, ?array $relations = []): Collection
    {
        $query = $this->createQuery()->whereIntegerInRaw('id', $ids);
        if ($gender) {
            $query = $query->where('gender', $gender);
        }
        if ($userStatus) {
            if (gettype($userStatus) == 'array') {
                $query = $query->whereIn('status', $userStatus);
            } else {
                $query = $query->where('status', $userStatus);
            }
        }
        if (!empty($relations)) {
            $query = $query->with($relations);
        }

        if (!empty($ids) && $query->get()->isEmpty()) {
            Log::error("query returned empty collection", ["user_ids" => json_encode($ids)]);
        }

        return $this->transformCollection($query->get());
    }

    /**
     * @param UserDomainModel $user
     * @return UserDomainModel
     * @throws \Exception
     */
    public function save(UserDomainModel $user): UserDomainModel
    {
        $userEntity = $this->createModelDAO($user->getId())->saveData($user);

        return $userEntity;
    }
}
