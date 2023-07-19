<?php

namespace Project\Domain\Base\Language\Enums;

use Project\UserInterface\Enums\StringEnum;

/**
 * @method static static English()
 * @method static static Japanese()
 */
final class Languages extends StringEnum
{
    const English = 'en';
    const Japanese = 'ja';

    public function toLangId(): int
    {
        if ($this->value == self::English) return 1;
        if ($this->value == self::Japanese) return 2;
    }

    /**
     * @param integer $id
     * @return string
     */
    static function getShortCodeById(int $id): string
    {
        if ($id == 1) return self::English;
        if ($id == 2) return self::Japanese;
    }
}
