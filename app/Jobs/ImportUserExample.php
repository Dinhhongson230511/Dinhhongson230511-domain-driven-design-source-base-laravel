<?php

namespace App\Jobs;

use Project\Domain\NotificationManagement\ImportLog\Enums\ImportStatus;
use Project\Domain\NotificationManagement\ImportLog\Enums\ImportType;
use Project\Domain\NotificationManagement\ImportLog\Models\ImportItemLog;
use Project\Port\Secondary\Database\NotificationManagement\ImportLog\Repository\EloquentImportItemLogRepository;
use Project\Port\Secondary\Database\UserManagement\User\Repository\UserRepository;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Exception;
use Project\Domain\UserManagement\UserCoupon\Services\UserCouponDomainService;


class ImportUserExample implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var UserRepository $userRepository
     * @var EloquentImportItemLogRepository $importItemLogRepository
     */
    private UserRepository $userRepository;
    private EloquentImportItemLogRepository $importItemLogRepository;
    private UserCouponDomainService $userCouponDomainService;

    /**
     * @var int $timeOut
     * @unit seconds
     * @description This is timeout run job.
     */
    static int $timeOut = 3;

    /**
     * Job trial time
     *
     * @var int
     */
    public int $tries = 1;

    /**
     * @var array $items
     */
    private array $items;

    /**
     * Create a new job instance.
     *  UserRepositoryInterface $userRepository
     * @param array $items
     */

    public function __construct(array $items)
    {
        $this->userRepository = resolve(UserRepository::class);
        $this->importItemLogRepository = resolve(EloquentImportItemLogRepository::class);
        $this->items = $items;
        $this->userCouponDomainService = resolve(UserCouponDomainService::class);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $batchId = $this->batch()->id;

        if (is_array($this->items) && !empty($this->items)) {
            foreach ($this->items as $item) {
                $this->saveUsersExample($batchId, $item);
            }
        }
    }

    /**
     * @param string $batchId
     * @param array $item
     * @return void
     * @functionName saveCouponForUsers
     * @description
     * - This is logic import multi coupon for users
     * - Save coupon for users to log
     */
    private function saveUsersExample(string $batchId, array $item): void
    {
        try {
            $userId = (int)$item['user_id'];
            $couponType = (string)$item['coupon_type'];
            $expiryDay = (int)$item['expiry_days_after'];

            // save as log
            $importItemLog = (new ImportItemLog($batchId, $userId, ImportType::IssueMultiCouponUser, $item, ImportStatus::Success, null));

            $user = $this->userRepository->getById($userId);

            if ($user) {
                $this->userCouponDomainService->issueCoupon($user, $couponType, $expiryDay);

                $this->importItemLogRepository->save($importItemLog);
                return;
            }

            $importItemLog->setStatus(ImportStatus::Fail)->setErrorMessage("User Not found");
            $this->importItemLogRepository->save($importItemLog);
            return;
        } catch (Exception $exception) {
            $userId = isset($item['user_id']) ? (int)$item['user_id'] : null;

            $importItemLog = (new ImportItemLog($batchId, $userId, ImportType::IssueMultiCouponUser, $item, ImportStatus::Fail, null));
            $importItemLog->setStatus(ImportStatus::Fail)->setErrorMessage($exception->getMessage());
            $this->importItemLogRepository->save($importItemLog);
            return;
        }
    }
}
