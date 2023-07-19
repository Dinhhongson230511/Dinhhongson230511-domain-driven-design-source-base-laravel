<?php

namespace App\Console\Commands\Notification;

use App\Console\Commands\BaseCommand;
use Project\Domain\NotificationManagement\Notification\EligibleReceivers\AbstractEligibleReceiver;
use Project\Domain\NotificationManagement\Notification\Factories\EligibleReceiversFactory;
use Project\Domain\NotificationManagement\Notification\Interfaces\NotificationRepositoryInterface;
use Project\Domain\NotificationManagement\Notification\Models\Notification;
use Project\Domain\NotificationManagement\Notification\Services\NotificationService;
use Project\Domain\UserManagement\User\Enums\UserGender;
use Project\Domain\UserManagement\User\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

abstract class AbstractNotificationSenderCommand extends BaseCommand
{
    protected NotificationRepositoryInterface $notificationRepository;

    protected NotificationService $notificationService;

    /**
     * @var AbstractEligibleReceiver
     */
    protected ?object $eligibleReceiver = null;

    protected ?int $eligibleGender = null;

    protected array $variableMapDatas;

    protected ?Collection $eligibleUsers = null;

    public function __construct(
        NotificationRepositoryInterface $notificationRepository,
        NotificationService $notificationService
    ) {
        $this->notificationRepository = $notificationRepository;
        $this->notificationService = $notificationService;

        parent::__construct();
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        Log::info('started to run' . $this->getKey());

        $this->setEligibleGenderFromArgument();

        if (is_null($this->getKey())) {
            Log::error('Notification key condition is incorrect.');
            $this->error("Notification key condition is incorrect.(or gender is wrong)");
            return;
        }

        $notification = $this->notificationRepository->getByKey($this->getKey());

        if (!$notification) {
            Log::error($this->getKey() . ' Notification is not found.');
            $this->error($this->getKey() . ' Notification is not found.');
            return;
        }

        $this->eligibleReceiver = $this->eligibleReceiver ?? EligibleReceiversFactory::create($notification->getEligibleUserKey());

        $this->eligibleUsers = $this->getEligibleReceiver()->retrieve();

        if ($this->eligibleUsers) {
            if ($this->getEligibleGender()) {
                $this->eligibleUsers = $this->eligibleUsers->filter(function ($user) {
                    return $user->getGender() == $this->getEligibleGender();
                });
            }

            $this->addVariableMapDatas();
            foreach ($this->eligibleUsers as $user) {
                $this->proceedSendingNotification($user, $notification);
            }
        }

        Log::info('finished to run' . $this->getKey());
    }

    /**
     * @return AbstractEligibleReceiver
     */
    protected function getEligibleReceiver()
    {
        return $this->eligibleReceiver;
    }

    /**
     * Override this function to map notification variables for each user and send notification
     *
     * @param User $user
     * @param Notification $notification
     */
    protected function proceedSendingNotification(User $user, Notification $notification): void
    {
        $this->notificationService->sendEmailNotificationToUser($user, $notification);
    }

    /**
     * Override this function to keep variable array data in $variableMapDatas
     *
     */
    protected function addVariableMapDatas(): void
    {
    }

    /**
     * @return string|null
     */
    abstract protected function getKey(): ?string;

    /**
     * Return UserGender Enum or NULL
     * @return int|null
     */
    protected function getEligibleGender(): ?int
    {
        return $this->eligibleGender;
    }

    protected function setEligibleGenderFromArgument(): void
    {
        $gender = $this->argument('gender');
        if ($gender) {
            $this->eligibleGender = ($gender == 'female') ? UserGender::Female : UserGender::Male;
        } else {
            $this->eligibleGender = null;
        }
    }
}
