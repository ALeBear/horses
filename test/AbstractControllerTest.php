<?php

namespace horses\test;

include_once __DIR__ . '/mock/MockController.php';
include_once __DIR__ . '/mock/MockConfig.php';
include_once __DIR__ . '/mock/MockView.php';
include_once __DIR__ . '/AbstractTest.php';

use Symfony\Component\DependencyInjection\ContainerBuilder;
use horses\test\mock\MockController;
use horses\test\mock\MockConfig;
use horses\test\mock\MockView;

class AbstractControllerTest extends AbstractTest
{
    /**
     * @var horses\test\mock\MockController
     */
    protected $controller;
    
    /**
     *
     * @var horses\test\mock\MockConfig 
     */
    protected $config;
    
    
    protected function setUp()
    {
        parent::setUp();
        $this->config = new MockConfig(array('view.meta' => array()));
        $DIContainer = new ContainerBuilder();
        $DIContainer->set('config', $this->config);
        $DIContainer->set('router', 'null');

        $this->controller = new MockController($DIContainer, new MockView('file', 'layoutFile'));
    }
    
    public function testDispatch()
    {
        $this->assertInstanceOf('horses\\AbstractController', $this->controller);
    }
    
    public function testRender()
    {
        $this->assertInstanceOf('horses\\AbstractController', $this->controller);
    }
    
    public function testPrepareMetas()
    {
        $this->assertInstanceOf('horses\\AbstractController', $this->controller);
    }
    
    public function testGetModule()
    {
        $this->assertInstanceOf('horses\\AbstractController', $this->controller);
    }
    
    public function testGetAction()
    {
        $this->assertInstanceOf('horses\\AbstractController', $this->controller);
    }
    
    public function testRedirect()
    {
        $this->assertInstanceOf('horses\\AbstractController', $this->controller);
    }
    
    public function tesRedirectToAction()
    {
        $this->assertInstanceOf('horses\\AbstractController', $this->controller);
    }
    
    public function testTranslate()
    {
        $this->assertInstanceOf('horses\\AbstractController', $this->controller);
    }
    
    public function testGetPartialFile()
    {
        $this->assertInstanceOf('horses\\AbstractController', $this->controller);
    }
    
    public function testCallMagicMethod()
    {
        $this->assertInstanceOf('horses\\AbstractController', $this->controller);
    }
}