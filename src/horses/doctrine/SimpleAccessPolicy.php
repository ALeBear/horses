<?php

namespace horses\doctrine;

use horses\auth\AccessControlException;
use horses\auth\AccessGrants;
use horses\auth\AccessGrantsNone;
use horses\auth\AccessPolicy;

class SimpleAccessPolicy implements AccessPolicy
{
    const TYPE_NEED_ALL = 'need_all';
    const TYPE_NEED_ANY = 'need_any';

    /** @var string */
    protected $type;
    /** @var string[] */
    protected $codesNeeded;

    /**
     * @param string[] $codesNeeded
     * @param string $type
     */
    public function __construct(array $codesNeeded, $type = self::TYPE_NEED_ANY)
    {
        $this->type = $type == self::TYPE_NEED_ALL ? $type : self::TYPE_NEED_ANY;
        $this->codesNeeded = $codesNeeded;
    }

    /**
     * @inheritdoc
     * @param SimpleAccessGrants $accessGrants
     */
    public function authorize(AccessGrants $accessGrants)
    {
        if (!count($this->codesNeeded)) {
            return;
        }
        if ($accessGrants instanceof AccessGrantsNone) {
            throw new AccessControlException(sprintf('Authorization failed for policy "%s", no grants', $this->__toString()));
        }

        $all = true;
        foreach ($this->codesNeeded as $aCodeNeeded) {
            foreach ($accessGrants as $aGrant) {
                /** @var SimpleAccessCode $aGrant */
                if ($aGrant->__toString() == $aCodeNeeded) {
                    if ($this->type == self::TYPE_NEED_ALL) {
                        continue 2;
                    }
                    if ($this->type == self::TYPE_NEED_ANY) {
                        return;
                    }
                }
                if ($this->type == self::TYPE_NEED_ALL) {
                    $all = false;
                    break 2;
                }
            }
        }

        if ($this->type == self::TYPE_NEED_ANY || !$all) {
            throw new AccessControlException(sprintf('Authorization failed for policy "%s" with grants "%s"', $this->__toString(), $accessGrants->__toString()));
        }
    }

    /** @inheritdoc */
    public function __toString()
    {
        return sprintf('[%s] %s: %s', get_class($this), $this->type, implode(',', $this->codesNeeded));
    }
}
