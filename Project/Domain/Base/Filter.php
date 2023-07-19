<?php

namespace Project\Domain\Base;

use Illuminate\Support\Collection;

class Filter
{
    protected Collection $conditions;

    protected int $limit = 0;

    protected int $offset = 0;

    /**
     * Filter constructor.
     * @param Condition[] $conditions
     */
    public function __construct(array $conditions = [])
    {
        $this->conditions = new Collection($conditions);
    }

    /**
     * @return Collection | Condition[]
     */
    public function getConditions(): Collection
    {
        return $this->conditions;
    }

    /**
     * @param Condition $condition
     * @return Filter
     */
    public function addCondition(Condition $condition): Filter
    {
        $this->conditions->add($condition);

        return $this;
    }

    /**
     * @param Collection | Condition[] $conditions
     * @return Filter
     */
    public function setConditions(Collection $conditions): Filter
    {
        $this->conditions = $conditions;

        return $this;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     * @return Filter
     */
    public function setLimit(int $limit): Filter
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     */
    public function setOffset(int $offset): Filter
    {
        $this->offset = $offset;

        return $this;
    }
}
