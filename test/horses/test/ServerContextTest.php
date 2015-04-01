<?php

namespace horses\test;

use horses\ServerContext;

class ServerContextTest extends AbstractTest
{
    /** @var ServerContext */
    protected $serverContext;
    
    
    protected function setUp()
    {
        parent::setUp();
        $this->serverContext = new ServerContext();
    }

    public function testGetEnvironment()
    {
        $this->assertEquals(ServerContext::DEFAULT_ENV, $this->serverContext->getEnvironment());
        $this->serverContext->set('ENV', 'foo');
        $this->assertEquals('foo', $this->serverContext->getEnvironment());
    }

    public function testGetApplication()
    {
        $this->assertNull($this->serverContext->getApplication());
        $this->serverContext->set('APP', 'bar');
        $this->assertEquals('bar', $this->serverContext->getApplication());
    }

    public function testGetPath()
    {
        $this->assertNull($this->serverContext->getPath('unknown'));
        $this->serverContext->set(ServerContext::DIR_ACTIONS, '/path/to/actions');
        $this->assertEquals('/path/to/actions', $this->serverContext->getPath(ServerContext::DIR_ACTIONS));
    }
}
