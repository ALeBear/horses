<?php

namespace horses\test\config;

use horses\test\AbstractTest;
use horses\config\Config;
use horses\config\Factory;

class FactoryTest extends AbstractTest
{
    /** @var Factory */
    protected $factory;
    
    
    protected function setUp()
    {
        parent::setUp();
        $loader = $this->getBasicMock('\horses\config\YamlFileLoader');
        $loader->expects($this->any())
            ->method('load')
            ->will($this->returnValue([
                ['blah' => 'overwrittennotdeep', 'onlyinfirst' => 'onlyinfirst', 'deep' => ['deepkey' => 'overwrittendeep']],
                ['blah' => 'pfff', 'onlyinsecond' => 'onlyinsecond', 'deep' => ['deepkey' => 'deepval']]
            ]));
        $this->factory = new Factory($loader, Config::class);
    }

    public function testGetConfig()
    {
        $this->assertEquals('pfff', $this->factory->getConfig('blah')->get('blah'));
        $this->assertEquals('onlyinfirst', $this->factory->getConfig('blah')->get('onlyinfirst'));
        $this->assertEquals('onlyinsecond', $this->factory->getConfig('blah')->get('onlyinsecond'));
        $this->assertEquals('deepval', $this->factory->getConfig('blah')->get('deep.deepkey'));
    }
}