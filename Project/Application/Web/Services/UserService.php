<?php

namespace Project\Application\Web\Services;

use Project\Domain\Base\Exception\BaseValidationException;
use Project\Domain\UserManagement\User\Models\User as UserDomainEntity;
use Project\Domain\UserManagement\User\Services\UserDomainService;
use Project\Infrastructure\Primary\WebApi\ResponseHandler\Api\ApiResponseHandler;
use Exception;
use Illuminate\Http\Response;
use Throwable;

class UserService
{
    /**
     * Response Status
     */
    protected $status;

    /**
     * Response Message
     */
    protected $message;

    /**
     * Response data
     *
     * @var array
     */
    protected $data = [];

    private UserDomainService $userDomainService;


    public function __construct(
        UserDomainService $userDomainService,
    ) {
        $this->userDomainService = $userDomainService;

        $this->status = Response::HTTP_OK;
        $this->message = __('api_messages.successful');
    }

    public function index()
    {
        $datas = $this->userDomainService->index();
        return  [
            'name' => $datas->getName()
        ];

        return $this;
    }

    /**
     * Format Registration data
     *
     * @return array
     */
    public function handleApiResponse(): array
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data
        ];
    }
}
