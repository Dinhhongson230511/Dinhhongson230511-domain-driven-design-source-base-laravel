<?php

namespace Project\Infrastructure\Secondary\Database\NotificationManagement\Sms\ModelDao;

use Project\Domain\Base\BaseDomainModel;
use Project\Infrastructure\Secondary\Database\Base\BaseModel;

class SmsWhitelist extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sms_whitelist';

    public function toDomainEntity()
    {
        // TODO: Implement toDomainEntity() method.
    }

    protected function fromDomainEntity(BaseDomainModel $model)
    {
        // TODO: Implement fromDomainEntity() method.
    }
}
