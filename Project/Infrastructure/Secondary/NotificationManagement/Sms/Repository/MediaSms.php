<?php

namespace Project\Infrastructure\Secondary\NotificationManagement\Sms\Repository;

use Project\Domain\NotificationManagement\Sms\Traits\MediaSmsVerificationContext;
use Project\Infrastructure\Secondary\Database\UserManagement\User\Repository\EloquentUserAuthRepository;
use Project\Infrastructure\Secondary\NotificationManagement\Sms\Interfaces\MobileNumberValidatorInterface;
use Project\Infrastructure\Secondary\NotificationManagement\Sms\Interfaces\SmsServiceProviderInterface;
use Project\Infrastructure\Secondary\NotificationManagement\Sms\Interfaces\VerificationContextInterface;
use GuzzleHttp\Client;
use Project\UserInterface\ResponseCodes\ApiCodes;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;

class MediaSms implements SmsServiceProviderInterface
{
    use MediaSmsVerificationContext;

    /*
     * Sets the endpoint of media sms
     */
    private $endPoint;

    /*
     * Sets the credential for media sms
     */
    private $credential;

    /*
     * Sets the client for media sms
     */
    /**
     * @var Client
     */
    private $client;

    /*
     * Set the response
     */
    private $response;

    /*
     * VerificationContextInterface
     */
    private $context;

    /**
     * @var string
     */
    private $mobileNumber;

    /*
     * MobileNumberValidatorInterface
     */
    private $validator;

    /**
     * MediaSms constructor.
     * @param string $mobileNumber
     * @throws BindingResolutionException
     */
    public function __construct (string $mobileNumber)
    {
        $this->mobileNumber = $mobileNumber;
        $this->context = app()->make(VerificationContextInterface::class);
        $this->validator = app()->make(MobileNumberValidatorInterface::class);

        // Instantiate service
        self::instantiateService();
    }

    /**
     *  Instantiate the service
     */
    public function instantiateService() : void
    {
        $this->endPoint = config('sms.api_endpoint');
        $this->client = new Client();
        $this->credential = base64_encode(config('sms.api_username') . ':' . config('sms.api_password'));
    }

    /**
     * Send sms via media sms provider
     *
     * @param string $formParams
     * @return MediaSms
     */
    public function sendSms( string $formParams ) : SmsServiceProviderInterface
    {
        $this->response = $this->validator->validate($this->mobileNumber) ?
            self::sendSmsViaService(json_decode($formParams , true)) :
            self::sendSmsViaServiceForNonProdEnvs();

        return  $this;
    }

    /**
     * Format verification code message
     *
     * @param int $verificationCode
     * @return string
     */
    public function getFormattedVerificationCode (int $verificationCode) : string
    {
        return self::getFormContext($this->mobileNumber,
            config('constants.sms_title_media'),
            $this->context->getVerificationContext($this->mobileNumber, $verificationCode)
        );
    }

    /**
     * Get formatted data to send sms notification
     *
     * @param string $message
     * @param string $title
     * @return string
     */
    public function formatDataToSend(string $message, string $title) : string
    {
        return self::getFormContext($this->mobileNumber, $title, $message);
    }

    /**
     * Handle api response
     *
     * @param array $data
     * @return array
     */
    public function handleApiResponse ( $data = [] ) : array
    {
        switch ($this->response['status'])
        {
            case 3 :
                if(!empty($this->response['response']) && $this->response['response'] == '{}')
                    return [
                        'status' => Response::HTTP_OK,
                        'message' => __('api_auth.code_sent_successfully'),
                        'data' => $data
                    ];
                    break;
        }
        return [
            'status' => ApiCodes::SOMETHING_WENT_WRONG,
            'message' => App::environment('production') ?
                __('api_auth.something_went_wrong') :
                $response['response'] ?? __('api_auth.something_went_wrong'),
            'data' => $data
        ];
    }

    /**
     * Send sms via service provider
     *
     * @param $formParams
     * @return array
     */
    protected function sendSmsViaService($formParams) :array
    {
        try {
            return [
                'status' => config('database_status.sms_queue_success'),
                'response' => json_encode($this->client->post($this->endPoint, [
                    'headers' => [
                        'Authorization' => 'Basic ' . $this->credential
                    ],
                    'form_params' => $formParams
                ]))
            ];
        } catch (\Throwable $exception) {
            return [
                'status' => config('database_status.sms_queue_failed')
            ];
        }
    }

    /**
     * Send sms for dev envs
     *
     * @return array
     */
    protected function sendSmsViaServiceForNonProdEnvs()
    {
        return [
            'status' => config('database_status.sms_queue_success'),
            'response' => '{}'
        ];
    }
}
