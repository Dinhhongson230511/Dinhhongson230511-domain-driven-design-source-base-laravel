<?php

namespace Project\Domain\NotificationManagement\Notification\EligibleReceivers;

use Project\Domain\UserManagement\User\Models\User;
use Illuminate\Support\Collection;

abstract class AbstractEligibleReceiver
{
    public ?string $datingSlot = null;

    /**
     * @return Collection|User[]
     */
    abstract public function retrieve(): ?Collection;

}
