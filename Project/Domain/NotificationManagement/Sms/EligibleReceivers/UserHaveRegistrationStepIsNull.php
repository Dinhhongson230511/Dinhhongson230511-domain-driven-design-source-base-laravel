<?php


namespace Project\Domain\NotificationManagement\Sms\EligibleReceivers;


use Project\Domain\Base\Condition;
use Project\Domain\Base\Filter;
use Project\Domain\NotificationManagement\Notification\EligibleReceivers\AbstractEligibleReceiver;
use Project\Domain\UserManagement\User\Enums\UserFilter;
use Project\Domain\UserManagement\User\Interfaces\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class UserHaveRegistrationStepIsNull extends AbstractEligibleReceiver
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function retrieve(): Collection
    {
        return $this->userRepository->getList(new Filter([
            Condition::make(UserFilter::MobileNumber, Condition::IS, Condition::NOTNULL),
            Condition::make(UserFilter::CreatedAt, Carbon::yesterday()->startOfDay(), Condition::GTEQ),
            Condition::make(UserFilter::CreatedAt, Carbon::yesterday()->endOfDay(), Condition::LTEQ),
        ]));
    }
}
