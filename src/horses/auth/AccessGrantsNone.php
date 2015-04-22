<?php

namespace horses\auth;

class AccessGrantsNone implements AccessGrants
{
    /** @inheritdoc */
    public function __toString()
    {
        return '';
    }
}