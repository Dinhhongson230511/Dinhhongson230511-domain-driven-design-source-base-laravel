<?php

namespace Project\UserInterface\ResponseCodes;

class ApiCodes
{
    // Add additional response codes here
    public const SOMETHING_WENT_WRONG = 512;
    public const INVALID_VERIFICATION_CODE = 513;
    public const NO_USER_FOUND = 514;
    public const DUPLICATE_USER_FOUND = 515;
    public const PREFECTURE_IN_USE = 516;
    public const AUTH_ID_MISMATCH = 517;
}
