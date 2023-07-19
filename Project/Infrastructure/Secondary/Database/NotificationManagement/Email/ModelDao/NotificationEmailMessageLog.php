<?php

namespace Project\Infrastructure\Secondary\Database\NotificationManagement\Email\ModelDao;

use Project\Domain\Base\BaseDomainModel;
use Project\Infrastructure\Secondary\Database\Base\BaseModel;

class NotificationEmailMessageLog extends BaseModel
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notification_email_message_logs';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->connection = env('LOG_DB_CONNECTION', 'mysql_logs');
    }


    /**
     * Create Domain Model object from this model DAO
     */
    public function toDomainEntity ()
    {
        // TODO: Implement toDomainEntity() method.
    }

    /**
     * Pull data from Domain Model object to this model DAO for saving
     * @param $model
     */
    protected function fromDomainEntity ( BaseDomainModel $model )
    {
        // TODO: Implement fromDomainEntity() method.
    }

}
