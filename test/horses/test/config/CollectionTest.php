<?php

namespace horses\test\config;

use horses\test\AbstractTest;
use horses\config\Collection;
use horses\config\InvalidCollectionKeyException;
use horses\config\UnknownConfigException;

class CollectionTest extends AbstractTest
{
    /** @var Collection */
    protected $collection;
    
    
    protected function setUp()
    {
        parent::setUp();
        $configOne = $this->getBasicMock('\horses\config\Config');
        $configTwo = $this->getBasicMock('\horses\config\Config');
        $configOne->expects($this->any())
            ->method('get')
            ->will($this->returnValue('foo'));
        $factory = $this->getBasicMock('\horses\config\Factory');
        $factory->expects($this->any())
            ->method('getConfig')
            ->will($this->returnValueMap([['one', $configOne], ['two', $configTwo]]));
        $this->collection = new Collection($factory);
        $this->collection->load('one')->load('two');
    }

    /**
     * @expectedException \horses\config\InvalidCollectionKeyException
     */
    public function testGetBadParam()
    {
        $this->collection->get('invalid');
    }

    /**
     * @expectedException \horses\config\UnknownConfigException
     */
    public function testGetNotLoaded()
    {
        $this->collection->get('does.not.exists');
    }

    public function testGet()
    {
        $this->assertEquals('foo', $this->collection->get('one.any'));
        $this->assertNull($this->collection->get('two.any'));
    }

    public function testGetWithDefault()
    {
        $this->assertNull($this->collection->get('two.any'));
    }

    /**
     * @expectedException \horses\config\InvalidCollectionKeyException
     */
    public function testSetBadParam()
    {
        $this->collection->set('invalid', 'bar');
    }

    /**
     * @expectedException \horses\config\UnknownConfigException
     */
    public function testSetNotLoaded()
    {
        $this->collection->get('does.not.exists', 'bar');
    }

    public function testSet()
    {
        $this->assertEquals('foo', $this->collection->set('one.any', 'newValue')->get('one.any'));
    }

    public function testGetSection()
    {
        $this->assertEquals('foo', $this->collection->getSection('one')->get('any'));
    }

    /**
     * @expectedException \horses\config\UnknownConfigException
     */
    public function testGetSectionHasNone()
    {
        $this->collection->getSection('greublah');
    }
}