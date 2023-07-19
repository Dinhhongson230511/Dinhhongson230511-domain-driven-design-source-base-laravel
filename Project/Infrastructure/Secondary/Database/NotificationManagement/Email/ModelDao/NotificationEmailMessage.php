<?php

namespace Project\Infrastructure\Secondary\Database\NotificationManagement\Email\ModelDao;

use Project\Domain\NotificationManagement\Email\Models\NotificationEmailMessage as EmailNotificationDomainModel;
use Project\Infrastructure\Secondary\Database\Base\BaseModel;
use Project\Infrastructure\Secondary\Database\NotificationManagement\Email\Traits\EmailNotificationRelationshipTrait;
use Project\Infrastructure\Secondary\Database\NotificationManagement\Email\Traits\HasFactory;
use Carbon\Carbon;

class NotificationEmailMessage extends BaseModel
{
    use HasFactory, EmailNotificationRelationshipTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notification_email_messages';
    protected $hidden = [];

    /**
     * @return EmailNotificationDomainModel
     */
    public function toDomainEntity()
    {
        $model = new EmailNotificationDomainModel(
            $this->user_id,
            $this->key,
            $this->title,
            $this->content,
            $this->status,
            $this->notification_id,
            (bool) $this->is_sms_sent,
            $this->read_at ? Carbon::createFromFormat('Y-m-d H:i:s', $this->read_at) : null,
            Carbon::make($this->created_at)
        );
        $model->setId($this->getKey());

        if ($this->relationLoaded('user')) {
            $model->setUser($this->user->toDomainEntity());
        }
        if ($this->relationLoaded('notification')) {
            $model->setNotification($this->notification->toDomainEntity());
        }

        return $model;
    }

    /**
     * @param EmailNotificationDomainModel $model
     * @return NotificationEmailMessage
     */
    protected function fromDomainEntity($model)
    {
        $this->user_id = $model->getUserId();
        $this->key = $model->getKey();
        $this->title = $model->getTitle();
        $this->content = $model->getContent();
        $this->status = $model->getStatus();
        $this->notification_id = $model->getNotificationId();
        $this->is_sms_sent = (int) $model->isSmsSent();
        $this->read_at = $model->getReadAt() ? $model->getReadAt()->toDateTimeString() : null;

        return $this;
    }
}
