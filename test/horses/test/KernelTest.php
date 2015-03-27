<?php

namespace horses\test\config;

use horses\ServerContext;
use horses\test\AbstractTest;
use horses\Kernel;

class KernelTest extends AbstractTest
{
    /** @var Kernel */
    protected $kernel;
    /** @var string */
    protected $rootPath;
    /** @var ServerContext */
    protected $serverContext;

    
    protected function setUp()
    {
        parent::setUp();
        $this->serverContext = new ServerContext();

        $this->rootPath = dirname(__DIR__) . '/fixtures';

        $this->kernel = new Kernel($this->rootPath, 'fooEnv', $this->serverContext);
    }

    public function testInit()
    {
        $this->assertEquals('fooEnv', $this->serverContext->getEnvironment());
        $this->assertEquals('fooApp', $this->serverContext->getApplication());
        $this->assertEquals($this->rootPath . '/src', $this->serverContext->getPath(ServerContext::DIR_SRC));
        $this->assertEquals($this->rootPath . '/action', $this->serverContext->getPath(ServerContext::DIR_ACTIONS));
        $this->assertEquals($this->rootPath . '/config', $this->serverContext->getPath(ServerContext::DIR_CONFIG));
        $this->assertEquals($this->rootPath . '/public', $this->serverContext->getPath(ServerContext::DIR_PUBLIC));
    }

    public function testGetFactories()
    {
        $this->assertInstanceOf('horses\Router', $this->kernel->getRouter());
        $this->assertInstanceOf('horses\auth\Authenticator', $this->kernel->getAuthenticator());
        $this->assertInstanceOf('horses\config\Collection', $this->kernel->getConfigCollection());
    }

    public function testGetServerContext()
    {
        $this->assertEquals($this->serverContext, $this->kernel->getServerContext());
    }

    /**
     * @expectedException \horses\KernelPanicException
     */
    public function testConfigDirDoesNotExists()
    {
        new Kernel('/foo', 'fooEnv', $this->serverContext);

    }
}