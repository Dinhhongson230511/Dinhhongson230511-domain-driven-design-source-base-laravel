<?php

namespace Project\Domain\UserManagement\User\Interfaces;

use Project\Domain\Base\Filter;
use Project\Domain\UserManagement\User\Models\User;
use Project\Domain\UserManagement\User\Models\User as UserDomainModel;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

interface UserRepositoryInterface
{
    /**
     * @param int $id
     * @param array|null $relations
     * @return User|null
     */
    public function getById(int $id, ?array $relations = []): ?User;

    /**
     * @param string $email
     * @return User|null
     */
    public function getUserByEmail(string $email): ?User;

    public function getByIdsRaw(array $ids, string $gender = null, int|array $userStatus = null, ?array $relations = []): Collection;

    /**
     * @param Filter $filter
     * @return Collection | User[]
     */
    public function getList(Filter $filter): Collection;

    /**
     * @param User $user
     * @return User
     */
    public function save(User $user): User;
}
