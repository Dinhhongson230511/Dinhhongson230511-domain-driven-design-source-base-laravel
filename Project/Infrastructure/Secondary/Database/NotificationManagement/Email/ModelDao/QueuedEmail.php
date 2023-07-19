<?php

namespace Project\Infrastructure\Secondary\Database\NotificationManagement\Email\ModelDao;

use Project\Domain\NotificationManagement\Email\Models\NotificationEmailMessage as EmailNotificationDomainModel;
use Project\Domain\NotificationManagement\Notification\Traits\NotificationExtractor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QueuedEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, NotificationExtractor;

    private EmailNotificationDomainModel $emailNotification;

    private string $content = '';

    public function __construct(EmailNotificationDomainModel $emailNotification)
    {
        $this->emailNotification = $emailNotification;
    }

    /**
     * Build the message.
     *
     * @return QueuedEmail
     */
    public function build()
    {
        $this->prepareContent();

        return $this->subject($this->emailNotification->getTitle())->html($this->content);
    }

    protected function prepareContent(): void
    {
        $this->content = $this->emailNotification->getContent();
        $this->convertLineBreaks();
        $this->convertTextLinks();
        $this->content .= $this->getImageReadTag($this->emailNotification->getId());
    }
}
