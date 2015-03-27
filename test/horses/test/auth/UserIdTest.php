<?php

namespace horses\test\config;

use horses\test\AbstractTest;
use horses\auth\UserId;

class UserIdTest extends AbstractTest
{
    /** @var UserId */
    protected $userId;
    
    
    protected function setUp()
    {
        parent::setUp();
        $this->userId = new UserId('test');
    }

    public function testGetId()
    {
        $this->assertEquals('test', $this->userId->getId());
    }
}