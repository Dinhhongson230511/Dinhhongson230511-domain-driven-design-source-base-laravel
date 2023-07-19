<?php

namespace Database\Seeders;

use Project\Domain\NotificationManagement\Notification\Interfaces\NotificationRepositoryInterface;
use Project\Domain\NotificationManagement\Notification\Models\Notification;
use Project\Infrastructure\Secondary\Database\NotificationManagement\Notification\ModelDao\Notification as ModelDaoNotification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class NotificationSeeder extends Seeder
{
    /**
     * @var NotificationRepositoryInterface
     */
    protected NotificationRepositoryInterface $notificationRepository;

    /**
     * NotificationSeeder constructor.
     * @param NotificationRepositoryInterface $notificationRepository
     */
    public function __construct(NotificationRepositoryInterface $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time_start = microtime(true);

        ModelDaoNotification::query()->delete();

        self::_seed();

        $time_end = microtime(true);

        Log::info('NotificationSeeder finished | took ' . ($time_end - $time_start) . 's');
    }

    /**
     *  Initiate the seeder
     */
    private function _seed()
    {
        try {
            $notifications = config('notification_seed_data');
            $notificationList = $this->notificationRepository->getAll();
            foreach ($notifications as $notification) {
                $newNotification = true;

                foreach ($notificationList as $item) {
                    if ($item->getKey() == $notification['key']) {
                        $newNotification = false;
                    }
                }

                $end = mb_strpos($notification['content'], "\n");
                $title = mb_substr($notification['content'], 0, $end, "UTF-8");

                if ($newNotification) {
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
                        $notification['follow_interval']
                    );
                    $this->notificationRepository->save($notificationEntity);
                }
            }
        } catch (\Exception $e) {
            Log::info('Have issue with ' . $notification['key']);
        }
    }
}
