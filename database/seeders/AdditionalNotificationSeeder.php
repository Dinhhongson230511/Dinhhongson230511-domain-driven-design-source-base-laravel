<?php

namespace Database\Seeders;

use Project\Domain\NotificationManagement\Notification\Interfaces\NotificationRepositoryInterface;
use Project\Domain\NotificationManagement\Notification\Models\Notification;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

/**
 * Class AdditionalNotificationSeeder
 * @package Database\Seeders
 * @example php artisan additional-notification-seeder --config=2022_03_29_add_new_registartion_reminder_for_male_female
 */
class AdditionalNotificationSeeder extends Seeder
{
    /**
     * @var NotificationRepositoryInterface
     */
    protected NotificationRepositoryInterface $notificationRepository;

    public function __construct()
    {
        $this->notificationRepository = app()->make(NotificationRepositoryInterface::class);
    }

    /**
     * Run the database seeds.
     *
     * @param string $config
     * @return void
     */
    public function run(string $config)
    {
        $time_start = microtime(true);

        self::_seed($config);

        $time_end = microtime(true);

        Log::info('AdditionalNotificationSeeder finished | took ' . ($time_end - $time_start) . 's');
    }

    /**
     *  Initiate the seeder
     * @param string $config
     */
    private function _seed(string $config)
    {
        try {
            $notifications = config('additionalNotifications.' . $config);

            $notificationList = $this->notificationRepository->getAll();
            foreach ($notifications as $notification) {
                $newNotification = true;
                /** @var Notification $item */
                foreach ($notificationList as $item) {
                    if ($item->getKey() == $notification['key'] && $item->getType() == $notification['type']) {
                        if (isset($notification['eligible_user_key'])) {
                            $item->setEligibleUserKey($notification['eligible_user_key']);
                        }
                        $item->setVariables($notification['variables'] ? explode(',', $notification['variables']) : []);
                        if (isset($notification['follow_interval'])) {
                            $item->setFollowInterval($notification['follow_interval']);
                        }
                        if (isset($notification['content'])) {
                            $item->setContent($notification['content']);
                        }
                        if (isset($notification['title'])) {
                            $item->setTitle($notification['title']);
                        }
                        //@todo update [redirect_to] property
                        if (isset($notification['redirect_to'])) {
                            $item->setRedirectTo($notification['redirect_to']);
                        }
                        $this->notificationRepository->save($item);
                        $newNotification = false;
                    }
                }

                if ($newNotification) {
                    if (!isset($notification['title']) || $notification['title'] == '') {
                        $end = mb_strpos($notification['content'], "\n");
                        $title = mb_substr($notification['content'], 0, $end, "UTF-8");
                    } else {
                        $title = $notification['title'];
                    }

                    $redirectTo = isset($notification['redirect_to']) ? $notification['redirect_to'] : null;

                    $notificationEntity = new Notification(
                        $notification['key'],
                        $notification['type'],
                        $title,
                        $notification['content'],
                        $notification['status'],
                        $notification['variables'] ? explode(',', $notification['variables']) : [],
                        $notification['label'],
                        $notification['eligible_user_key'],
                        $notification['prefectures'] ? explode(',', $notification['prefectures']) : [],
                        $notification['follow_interval'],
                        $redirectTo
                    );
                    $this->notificationRepository->save($notificationEntity);
                }
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
