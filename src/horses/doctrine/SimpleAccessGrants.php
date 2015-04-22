<?php

namespace horses\doctrine;

use horses\auth\AccessGrants;
use IteratorAggregate;
use ArrayIterator;

class SimpleAccessGrants implements AccessGrants, IteratorAggregate
{
    /** @var SimpleAccessCode[]  */
    protected $codes;

    /**
     * @param SimpleAccessCode[] $codes
     */
    public function __construct(array $codes)
    {
        $this->codes = $codes;
    }

    /** @inheritdoc */
    public function getIterator()
    {
        return new ArrayIterator($this->codes);
    }

    /** @inheritdoc */
    public function __toString()
    {
        return implode(',', $this->codes);
    }
}
