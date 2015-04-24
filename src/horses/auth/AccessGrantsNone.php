<?php

namespace horses\auth;

/** @codeCoverageIgnore */
class AccessGrantsNone implements AccessGrants
{
    /** @inheritdoc */
    public function __toString()
    {
        return '';
    }
}