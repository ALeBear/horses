<?php

namespace horses\doctrine;

/**
 * @Entity
 */
class SimpleAccessCode
{
    /**
     * @Id @Column(type="integer") @GeneratedValue
     * @param integer
     */
    protected $id;

    /**
     * @Column(type="integer")
     * @param integer
     */
    protected $userId;

    /**
     * @Column(type="string", length=30)
     * @var string
     */
    protected $code;

    /**
     * @param string $code
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->code;
    }
}
