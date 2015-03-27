<?php

namespace horses\test\config;

use horses\test\AbstractTest;
use horses\config\YamlFileLoader;

class YamlFileLoaderTest extends AbstractTest
{
    /** @var YamlFileLoader */
    protected $yamlFileLoader;

    
    protected function setUp()
    {
        parent::setUp();
        $fileLocator = $this->getBasicMock('\Symfony\Component\Config\FileLocatorInterface');
        $fileLocator->expects($this->any())
            ->method('locate')
            ->will($this->returnCallback(function($resource) {
                if ($resource == 'config.yml') return [dirname(dirname(__DIR__)) . '/fixtures/config/config.yml'];
                throw new \InvalidArgumentException();
            }));
        $this->yamlFileLoader = new YamlFileLoader($fileLocator);
    }

    public function testLoad()
    {
        $result = [['foo' => 'bar', 'greu' => ['one', 'two']]];
        $this->assertEquals($result, $this->yamlFileLoader->load('config.yml'));
        $this->assertEquals($result, $this->yamlFileLoader->load('config'));
        $this->assertEquals([[]], $this->yamlFileLoader->load('unknown'));
    }

    public function testSupports()
    {
        $this->assertTrue($this->yamlFileLoader->supports('config.yml'));
        $this->assertFalse($this->yamlFileLoader->supports('config'));
    }
}