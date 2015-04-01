<?php

namespace horses\test\auth;

use horses\test\AbstractTest;
use horses\auth\UsernamePasswordCredentials;

class UserIdTest extends AbstractTest
{
    /** @var UsernamePasswordCredentials */
    protected $creds;
    
    
    protected function setUp()
    {
        parent::setUp();
        $this->creds = new UsernamePasswordCredentials('test', 'foo');
    }

    public function testAccessors()
    {
        $this->assertEquals('test', $this->creds->getUsername());
        $this->assertEquals('foo', $this->creds->getPassword());
    }
}
