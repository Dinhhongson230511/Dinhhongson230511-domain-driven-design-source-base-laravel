<?php

namespace Project\Infrastructure\Secondary\Database\NotificationManagement\Notification\ModelDao;

use Project\Domain\Base\BaseDomainModel;
use Project\Infrastructure\Secondary\Database\Base\BaseModel;
use Project\Infrastructure\Secondary\Database\NotificationManagement\Notification\Traits\HasUserNotificationExclusionFactory;
use Project\Domain\NotificationManagement\Notification\Models\UserNotificationExclusion as UserNotificationExclusionDomainModel;

class UserNotificationExclusion extends BaseModel
{
    use HasUserNotificationExclusionFactory;
    /**
     * @var string
     */
    protected $table = 'user_notification_exclusion';

    protected $hidden = [];

    /**
     * Create Domain Model object from this model DAO
     */
    public function toDomainEntity()
    {
        $model = new UserNotificationExclusionDomainModel(
            $this->user_id,
            $this->notification_key,
            $this->status,
            $this->type
        );
        $model->setId($this->getKey());
        $model->setUpdatedAt($this->updated_at);
        $model->setCreatedAt($this->created_at);

        return $model;
    }

    /**
     * @param UserNotificationExclusionDomainModel $model
     * @return UserNotificationExclusion
     */
    protected function fromDomainEntity($model)
    {
        $this->user_id = $model->getUserId();
        $this->notification_key = $model->getKey();
        $this->status = $model->getStatus();
        $this->type = $model->getType();

        return $this;
    }
}
