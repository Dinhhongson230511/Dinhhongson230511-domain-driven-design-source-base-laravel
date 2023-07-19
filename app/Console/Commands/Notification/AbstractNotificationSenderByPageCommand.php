<?php

namespace App\Console\Commands\Notification;

use Project\Domain\NotificationManagement\Notification\EligibleReceivers\AbstractEligibleReceiverByPage;
use Project\Domain\NotificationManagement\Notification\Factories\EligibleReceiversFactory;
use Project\Domain\NotificationManagement\Notification\Interfaces\NotificationRepositoryInterface;
use Project\Domain\NotificationManagement\Notification\Services\NotificationService;
use Project\Domain\UserManagement\User\Interfaces\UserRepositoryInterface;
use Project\UserInterface\Helpers\Utility;
use Illuminate\Support\Facades\Log;

abstract class AbstractNotificationSenderByPageCommand extends AbstractNotificationSenderCommand
{
    const PAGE_SIZE = 50;
    /**
     * @var AbstractEligibleReceiverByPage
     */
    protected ?object $eligibleReceiverByPage = null;
    protected UserRepositoryInterface $userRepository;

    public function __construct(
        NotificationRepositoryInterface $notificationRepository,
        NotificationService $notificationService,
        UserRepositoryInterface $userRepository,
    ) {
        $this->userRepository = $userRepository;
        parent::__construct($notificationRepository, $notificationService);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        $this->setEligibleGenderFromArgument();
        Log::info($this->getKey() . ' is running');

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

        $this->eligibleReceiverByPage = $this->eligibleReceiverByPage ?? EligibleReceiversFactory::create($notification->getEligibleUserKey());

        $userIds = array_unique($this->eligibleReceiverByPage->retrieveUserIds());
        $this->info("retrievedUserId result : " . count($userIds));

        $perChunkAction = function ($eligibleUsers) use ($notification) {
            if (empty($eligibleUsers)) return;
            $this->eligibleUsers = $this->eligibleReceiverByPage->filterEligibleUsers($eligibleUsers);

            if ($this->getEligibleGender()) {
                $this->eligibleUsers = $this->eligibleUsers->filter(function ($user) {
                    return $user->getGender() == $this->getEligibleGender();
                });
            }
            if (!$this->eligibleUsers->count()) return;

            $this->addVariableMapDatas();
            $this->info("sending process : count " . $this->eligibleUsers->count() . " user " . $this->eligibleUsers->first()->getId() . " to " . $this->eligibleUsers->last()->getId());
            foreach ($this->eligibleUsers as $user) {
                logger($this->getKey() . ' per page will be sent to ' . $user->getId());
                $this->proceedSendingNotification($user, $notification);
                logger($this->getKey() . ' per page was sent to ' . $user->getId());
            }
        };

        $fetchChunk = function ($page, $perPage) use ($userIds) {
            return $this->userRepository->getByIdsRaw(array_slice($userIds, ($page - 1) * $perPage, $perPage));
        };

        Utility::chunk($fetchChunk, $perChunkAction);
        Log::info($this->getKey() . ' finishes');
    }
}
