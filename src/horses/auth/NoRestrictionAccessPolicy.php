<?php

namespace horses\auth;

class NoRestrictionAccessPolicy implements AccessPolicy
{
    /**
     * @return string
     */
    public function __toString()
    {
        return get_class($this);
    }

    /** @inheritdoc */
    public function authorize(AccessGrants $accessGrant)
    {
        return true;
    }
}