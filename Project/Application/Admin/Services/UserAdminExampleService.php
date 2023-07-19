<?php

namespace Project\Application\Admin\Services;

use Project\Application\Admin\Services\Interfaces\UserAdminExampleServiceInterface;
use Project\Domain\UserManagement\User\Interfaces\UserRepositoryInterface;
use Project\Domain\UserManagement\UserCoupon\Services\UserCouponDomainService;
use Illuminate\Http\Response;
use App\Imports\TestingUserImport;
use Maatwebsite\Excel\Excel;
use App\Jobs\ImportUserExample;
use Illuminate\Support\Facades\Bus;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Imports\ValidateTestingUser;
use Maatwebsite\Excel\Facades\Excel as ImportExcel;

class UserAdminExampleService implements UserAdminExampleServiceInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    // /**
    //  * @var UserCouponDomainService
    //  */
    // private UserCouponDomainService $userCouponDomainService;

    /**
     * @var int
     */
    private $status;

    /**
     * @var string
     */
    private $message;

    /**
     * @var array
     */
    private $data = [];

    /**
     * AdminCouponService constructor.
     * @param UserRepositoryInterface $userRepository
     * @param UserCouponDomainService $userCoupon
     */
    public function __construct(
        UserRepositoryInterface $userRepository, 
        // UserCouponDomainService $userCouponDomainService
    )
    {
        $this->userRepository = $userRepository;
        // $this->userCouponDomainService = $userCouponDomainService;

        $this->status = Response::HTTP_OK;
        $this->message = __('api_messages.successful');
    }

    /**
     *
     * @param string $filePath
     * @return AdminCouponServiceInterface
     * @throws \Exception
     */
    public function issueImportCoupons($filePath): UserAdminExampleServiceInterface
    {
        $validator = new ValidateTestingUser();
        ImportExcel::import($validator, $filePath);

        if ($validator->getFailures()) {
            $this->status = Response::HTTP_INTERNAL_SERVER_ERROR;
            $this->data = $validator->getFailures();
            $this->message = __('api_messages.userCoupon.import_fail');
        } else {
            $items = (new TestingUserImport())->toCollection($filePath, null, Excel::CSV)->first();
            $itemsListChunkSize = array_chunk($items->toArray(), (int)config('batch.chunkSize'));

            $jobList = [];
            foreach ($itemsListChunkSize as $data) {
                $jobList[] = new ImportUserExample($data);
            }

            $batch = Bus::batch($jobList)
                ->then(function (Batch $batch) {
                    Log::info("All jobs completed successfully...");
                })
                ->catch(function (Batch $batch, Throwable $e) {
                    Log::info("First batch job failure detected...");
                })
                ->finally(function (Batch $batch) {
                    Log::info("finally");
                })
                ->name(config('batch.importMultiCouponUser'))
                ->onConnection(config('batch.connectionName'))
                ->onQueue(config('batch.queueName'))
                ->dispatch();

            $this->data = [
                "success" => true,
                "batchId" => $batch->id,
            ];

            $this->message = __('api_messages.userCoupon.import_success');
        }

        return $this;
    }

    /**
     * Format response data
     *
     * @return array
     */
    public function handleApiResponse() : array
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data
        ];
    }
}
