<?php

namespace Project\Domain\NotificationManagement\Notification\EligibleReceivers;

use Illuminate\Support\Collection;

abstract class AbstractEligibleReceiverByPage
{
    public ?string $datingSlot = null;

    /**
     * @return array
     */
    abstract public function retrieveUserIds(): array;

    abstract public function filterEligibleUsers(Collection $users): Collection;

}
