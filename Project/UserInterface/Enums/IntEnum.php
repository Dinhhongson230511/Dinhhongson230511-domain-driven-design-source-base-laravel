<?php

namespace Project\UserInterface\Enums;

use BenSampo\Enum\Enum;

abstract class IntEnum extends Enum
{
    /**
     * Used to parse the value while retrieving from the database
     *
     * @param mixed $value
     * @return int|mixed
     */
    public static function parseDatabase($value)
    {
        return (int) $value;
    }

    /**
     * Convert value to Int
     *
     * @return int
     */
    public function toInt() : int
    {
        return array_search($this->value, self::getValues()) + 1;
    }

    /**
     * Convert value to Int
     *
     * @param $position
     * @return string
     */
    public static function fromIndex(int $position) : string
    {
        $position--;
        return self::getValues()[$position];
    }
}
