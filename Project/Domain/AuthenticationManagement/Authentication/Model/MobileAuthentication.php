<?php

namespace Project\Domain\AuthenticationManagement\Authentication\Model;

use Project\Domain\AuthenticationManagement\Authentication\Enums\UserAuthType;
use Project\Domain\Base\Measurement\Enums\FBCVEventNames;
use Project\Domain\MasterDataManagement\AppVersion\Interfaces\AppVersionRepositoryInterface;
use Project\Domain\UserManagement\User\Enums\UserGender;
use Project\Domain\UserManagement\User\Events\CreatedFirstUserData;
use Project\Domain\UserManagement\User\Interfaces\UserRepositoryInterface;
use Project\Domain\UserManagement\User\Models\User as UserEntity;
use Project\Domain\UserManagement\UserInfoUpdatedTime\Models\UserInfoUpdatedTime;
use Project\Domain\UserManagement\UserMetaData\Interfaces\UserMetaDataInterface;
use Project\Domain\UserManagement\UserMetaData\Models\UserMetaData as UserMetaDataEntity;
use Project\Infrastructure\Secondary\Database\UserManagement\User\ModelDao\UserAuth;
use Project\UserInterface\Helpers\Utility;
use Carbon\Carbon;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cookie;

class MobileAuthentication extends Authentication
{
    /*
     * @var Auth Id
     */
    private ?string $mobileNumber;

    /*
     * @var query parameter passed to landing page
     */
    private ?string $lpQueryStr;
    private ?string $appVersion;

    private ?string $oneSignalPlayerId;

    private UserRepositoryInterface $userRepository;
    private UserMetaDataInterface $userMetaDataRepository;
    private AppVersionRepositoryInterface $appVersionRepository;

    /**
     * AuthenticationService constructor.
     *
     * @param string $authId
     * @param string|null $lpQueryStr
     * @param string $authType
     * @throws BindingResolutionException
     */
    public function __construct(string $authId, ?string $lpQueryStr, ?string $oneSignalPlayerId, ?string $appVersion, string $authType = UserAuthType::Mobile)
    {
        parent::__construct($authId, $authType);
        $this->mobileNumber = Utility::decode($this->authId);
        $this->lpQueryStr = $lpQueryStr;
        $this->appVersion = $appVersion;
        $this->oneSignalPlayerId = $oneSignalPlayerId;
        $this->userRepository = app()->make(UserRepositoryInterface::class);
        $this->userMetaDataRepository = app()->make(UserMetaDataInterface::class);
        $this->appVersionRepository = app()->make(AppVersionRepositoryInterface::class);
    }

    /**
     * Retrieve User
     *
     * @param Builder $userAuthQueryBuilder
     * @return UserAuth
     */
    protected function retrieveUser(Builder $userAuthQueryBuilder): UserAuth
    {
        if(empty($this->userCount)) {
            return $this->userService->createAndRetrieveNewUserAuth($this->authId, $this->createFirstUserData());
        }else {
            $userAuth =  $this->userService->updateAndRetrieveUserData($userAuthQueryBuilder, $this->getUserDataToUpdate());
            if($this->oneSignalPlayerId){
                $userMetaData = $this->userMetaDataRepository->retrieveUserMetaDataByUserId($userAuth->user_id);
                if(!$userMetaData){
                    $userMetaData = (new UserMetaDataEntity($userAuth->user_id, $this->oneSignalPlayerId));
                }
                $userMetaData->setOneSignalPlayerId($this->oneSignalPlayerId);
                $this->userMetaDataRepository->save($userMetaData);
            }

            $reviewAppVersion = $this->appVersion? $this->appVersionRepository->getByVersion($this->appVersion) :null;
            if($reviewAppVersion && $reviewAppVersion->isReviewTerm()){
                $user = $this->userRepository->getById($userAuth->user_id);
                $user->setSecondMode(true);
                $this->userRepository->save($user);
                // temporary update second mode since the data has been take from above.
                $userAuth->user->second_mode = 1;
            }
            return $userAuth;
        }
    }

    /**
     * Get user auth data
     *
     * @return array
     */
    private function createFirstUserData(): array
    {
        if ($this->lpQueryStr && $this->lpQueryStr[0] == '?') {
            $this->lpQueryStr = mb_substr($this->lpQueryStr, 1);
        }

        $queryParam = [];
        parse_str($this->lpQueryStr, $queryParam);

        $reviewAppVersion = $this->appVersion? $this->appVersionRepository->getByVersion($this->appVersion) :null;

        $user = (new UserEntity(
            gender: in_array($queryParam['gender'] ?? null, UserGender::getValues())
                ? $queryParam['gender'] : null,
            mobileNumber: $this->mobileNumber,
            lpQueryStr: $this->lpQueryStr,
            fbp: $queryParam['fbp'] ?? null,
            fbc: $queryParam['fbc'] ?? null,
            secondMode:  $reviewAppVersion? $reviewAppVersion->isReviewTerm(): false,
            userInfoUpdatedTime: (new UserInfoUpdatedTime())
        ));

        $user = $this->userRepository->save($user);

        //init user metadata
        $userMetaData = (new UserMetaDataEntity($user->getId(), $this->oneSignalPlayerId));
        $userMetaData = $this->userMetaDataRepository->save($userMetaData);
        $user->setUserMetaData($userMetaData);

        CreatedFirstUserData::dispatch($user, [FBCVEventNames::SubmitApplication]);

        return [
            'user_id' => $user->getId(),
            'auth_id' => $this->authId,
            'auth_type' => UserAuthType::Mobile
        ];
    }

    /**
     * Get user data to update
     *
     * @return array
     */
    private function getUserDataToUpdate(): array
    {
        return [
            'auth_type' => $this->authType,
            'auth_id' => $this->authId
        ];
    }
}
