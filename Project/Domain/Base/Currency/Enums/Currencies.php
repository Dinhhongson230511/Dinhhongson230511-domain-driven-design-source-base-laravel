<?php

namespace Project\Domain\Base\Currency\Enums;

use Project\UserInterface\Enums\StringEnum;

/**
 * @method static static Dollars()
 * @method static static JapaneseYen()
 */
final class Currencies extends StringEnum
{
    const Dollars = '$';
    const JapaneseYen = '¥';
}
