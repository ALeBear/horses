<?php

namespace horses\test\config;

use horses\test\AbstractTest;
use horses\config\Config;

class ConfigTest extends AbstractTest
{
    /**
     * @var Config
     */
    protected $config;
    
    
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
        $this->config = new Config('test', $loader);
    }

    public function testGet()
    {
        $this->assertEquals('pfff', $this->config->get('blah'));
        $this->assertEquals('onlyinfirst', $this->config->get('onlyinfirst'));
        $this->assertEquals('onlyinsecond', $this->config->get('onlyinsecond'));
        $this->assertEquals('deepval', $this->config->get('deep.deepkey'));
    }

    public function testGetWithDefault()
    {
        $this->assertNull($this->config->get('notakey'));
        $this->assertEquals('default', $this->config->get('notakey', 'default'));
    }

    public function testSet()
    {
        $this->assertEquals('newValue', $this->config->set('blah', 'newValue')->get('blah'));
        $this->assertEquals('newValue', $this->config->set('newkey', 'newValue')->get('newkey'));
        $this->assertEquals('newValue', $this->config->get('newkey'));
        $this->assertEquals('newValue', $this->config->set('newkey.deep', 'newValue')->get('newkey.deep'));
    }
}