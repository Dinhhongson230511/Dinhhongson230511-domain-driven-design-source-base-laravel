<?php

namespace Project\Domain\Base\Country\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Japan()
 * @method static static Vietnam()
 */
final class Countries extends Enum
{
    const Japan = [
        'name' => 'Japan',
        'default_language_id' => 2,
        'default_currency_id' => 2,
        'country_code' => '81'
    ];
    const Vietnam = [
        'name' => 'Vietnam',
        'default_language_id' => 1,
        'default_currency_id' => 1,
        'country_code' => '84'
    ];
}
