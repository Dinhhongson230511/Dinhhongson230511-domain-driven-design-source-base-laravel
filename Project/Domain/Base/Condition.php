<?php

namespace Project\Domain\Base;

class Condition
{
    const EQ = 'equal';
    const NOTEQ = 'not_equal';
    const IN = 'in';
    const INRAW = 'integer_in_raw';
    const NOTIN = 'not_in';
    const GT = 'greater_than';
    const GTEQ = 'greater_than_or_equal';
    const LT = 'less_than';
    const LTEQ = 'less_than_or_equal';
    const NULL = 'null';
    const NOTNULL = 'not_null';
    const LIKE = 'like';
    const IS = 'is';

    protected string $field = '';

    protected $value = null;

    protected string $type = self::EQ;

    /**
     * @param string $field
     * @param mixed $value
     * @param string $type
     */
    public function __construct(string $field, $value, string $type = self::EQ)
    {
        $this->field = $field;
        $this->value = $value;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @param string $field
     */
    public function setField(string $field): void
    {
        $this->field = $field;
    }

    /**
     * @return mixed|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed|null $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @param string $field
     * @param $value
     * @param string $type
     * @return Condition
     */
    public static function make(string $field, $value, string $type = self::EQ): Condition
    {
        return new static($field, $value, $type);
    }
}
