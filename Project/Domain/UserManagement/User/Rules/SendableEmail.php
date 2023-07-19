<?php
namespace Project\Domain\UserManagement\User\Rules;

use Project\Domain\UserManagement\User\Interfaces\SendableEmailRepositoryInterface;
use Illuminate\Contracts\Validation\Rule;

class SendableEmail implements Rule
{

    /**
     * @var SendableEmailRepositoryInterface
     */
    private SendableEmailRepositoryInterface $sendableEmailRepository;

    /**
     * Create a new rule instance.
     */
    public function __construct()
    {
        $this->sendableEmailRepository = app(SendableEmailRepositoryInterface::class);
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
        return $this->sendableEmailRepository->verifyEmail($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('api_messages.registration.error_invalid_email');
    }
}
