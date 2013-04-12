<?php

namespace horses\test;

include_once __DIR__ . '/mock/MockController.php';
include_once __DIR__ . '/mock/MockConfig.php';
include_once __DIR__ . '/mock/MockView.php';

use PHPUnit_Framework_TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use horses\test\mock\MockController;
use horses\test\mock\MockConfig;
use horses\test\mock\MockView;

class AbstractControllerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var horses\test\mock\MockController
     */
    protected $controller;
    
    
    protected function setUp()
    {
        parent::setUp();
        $conf = new MockConfig(array());
        $DIContainer = new ContainerBuilder();
        $DIContainer->set('config', $conf)
            ->set('router', null);

        $this->controller = new MockController($DIContainer, new MockView('file', 'layoutFile'));
    }
    public function testLocate()
    {
        $this->assertInstanceOf('horses\\plugin\\main\\Plugin', $locator->locate('main'));
    }
}