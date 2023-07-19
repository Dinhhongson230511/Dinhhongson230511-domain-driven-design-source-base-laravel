<?php

namespace Project\UserInterface\Enums;

use BenSampo\Enum\Enum;

class StringEnum extends Enum
{
    /**
     * Convert value to Int
     *
     * @return int
     */
    public function toInt() : int
    {
        return array_search($this->value, self::getValues()) + 1;
    }
}
