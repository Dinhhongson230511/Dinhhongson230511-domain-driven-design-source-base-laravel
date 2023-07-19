<?php

namespace Project\Application\Admin\Services\Interfaces;

interface UserAdminExampleServiceInterface
{
    /**
     * Import Issue coupon
     *
     * @param $userCoupon
     * @return UserAdminExampleServiceInterface
     */
    public function issueImportCoupons($userCoupon): UserAdminExampleServiceInterface;

    /**
     * Format response data
     *
     * @return array
     */
    public function handleApiResponse() : array;
}
