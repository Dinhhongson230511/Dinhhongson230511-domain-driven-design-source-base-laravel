<?php

namespace Project\Domain\UserManagement\User\Services;

use Project\Domain\Base\Exception\BaseValidationException;
use Project\Domain\Base\Exception\BaseValidationMessages;
use Project\Domain\UserManagement\User\Interfaces\UserRepositoryInterface;
use Project\Domain\UserManagement\User\Rules\ExistsEmail;
use Project\Domain\UserManagement\User\Rules\SendableEmail;
use Project\Domain\UserManagement\User\Models\User as UserEntity;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;
use Project\Infrastructure\Secondary\Database\UserManagement\User\Interfaces\EloquentUserAuthInterface;

class UserDomainService
{
    private UserRepositoryInterface $userRepository;

    private EloquentUserAuthInterface $userAuthRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        EloquentUserAuthInterface $userAuthRepository,
    ) {
        $this->userAuthRepository = $userAuthRepository;
        $this->userRepository = $userRepository;
    }

    public function index() {
        return $this->userRepository->getById(1);
    }

    /**
     * @param UserEntity $user
     * @param $email
     * @throws Exception
     */
    public function setEmailIfValid(UserEntity $user, $email)
    {
        $validator = Validator::make(['email' => $email], ['email' => [new SendableEmail, new ExistsEmail]]);
        if ($validator->fails()) {
            throw new BaseValidationException($validator);
        }
        $user->setEmail($email);
    }

    /**
     * Retrieve user auth query builder
     *
     * @param string $authId
     * @return mixed
     */
    public function retrieveUserAuthByAuthId(string $authId): Builder
    {
        // Retrieve the user auth query builder ( helpful in case we cant to add additional conditions to it )
        return $this->userAuthRepository->retrieveUserAuthViaAuthIdQueryBuilder($authId);
    }
}
