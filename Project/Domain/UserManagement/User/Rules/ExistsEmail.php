<?php

namespace Project\Domain\UserManagement\User\Rules;

use Project\Domain\UserManagement\User\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ExistsEmail implements Rule
{

    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->userRepository = app(UserRepositoryInterface::class);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $user = Auth::user()->getDomainEntity();
        if($user->getEmail() === $value){
            return true;
        }

        return empty($this->userRepository->getUserByEmail($value));
    }
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('api_messages.registration.error_exists_email');
    }
}
