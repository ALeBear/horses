<?php

namespace horses\test;

include __DIR__ . '/mock/MockPluginLocator.php';
include __DIR__ . '/mock/MockPlugin.php';
include __DIR__ . '/AbstractTest.php';

use horses\Kernel;
use horses\test\mock\MockPluginLocator;
use horses\test\mock\MockPlugin;
use Symfony\Component\HttpFoundation\Session\Session;

class KernelTest extends AbstractTest
{
    /**
     * @var horses\Kernel
     */
    protected $kernel;
    
    /**
     * @var horses\IPlugin[]
     */
    protected $plugins;
    
    /**
     * @var Symfony\Component\HttpFoundation\Request
     */
    protected $request;
    
    
    protected function setUp()
    {
        parent::setUp();
        $this->request = $this->getRequest('/', array());
        $this->plugins = array(
            'main' => new MockPlugin('main', 1),
            'other' => new MockPlugin('other', 5),
            'last' => new MockPlugin('last', 6));
        $this->kernel = Kernel::factory()
            ->injectPluginLocator(new MockPluginLocator($this->plugins))
            ->injectRequest($this->request)
            ->injectSession(new Session());
    }
    
    public function testRequestAttributes()
    {
        $this->kernel->run('/base/dir', array());
        $this->assertFalse($this->request->attributes->get('BOOTSTRAP_ONLY'));
        $this->assertEquals(isset($_SERVER['ENV']) ? $_SERVER['ENV'] : Kernel::DEFAULT_ENV, $this->request->attributes->get('ENV'));
        $this->assertEquals('/base/dir', $this->request->attributes->get('DIR_BASE'));
        $this->assertEquals('/base/dir/application', $this->request->attributes->get('DIR_APPLICATION'));
        $this->assertEquals('/base/dir/lib', $this->request->attributes->get('DIR_LIB'));
        $this->assertEquals('/base/dir/application/controller', $this->request->attributes->get('DIR_CONTROLLERS'));
    }
    
    public function testPluginsBootstrap()
    {
        $this->kernel->run('/base/dir', array('last', 'other'));
        $this->assertNotNull($this->plugins['main']->bootstrapped);
        $this->assertNotNull($this->plugins['other']->bootstrapped);
        $this->assertNotNull($this->plugins['last']->bootstrapped);
        $this->assertGreaterThan($this->plugins['main']->bootstrapped, $this->plugins['other']->bootstrapped);
        $this->assertGreaterThan($this->plugins['other']->bootstrapped, $this->plugins['last']->bootstrapped);
    }
    
    public function testPluginsDispatch()
    {
        $this->kernel->run('/base/dir', array('last', 'other'));
        $this->assertNotNull($this->plugins['main']->dispatched);
        $this->assertNotNull($this->plugins['other']->dispatched);
        $this->assertNotNull($this->plugins['last']->dispatched);
        $this->assertGreaterThan($this->plugins['last']->dispatched, $this->plugins['main']->dispatched);
        $this->assertGreaterThan($this->plugins['other']->dispatched, $this->plugins['last']->dispatched);
    }
}