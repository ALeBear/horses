<?php

namespace horses\test;

use horses\Request;

class RequestTest extends AbstractTest
{
    /** @var Request */
    protected $request;

    
    protected function setUp()
    {
        parent::setUp();

        $_GET = ['foo' => 'bar', 'other' => 'notbar'];
        $_POST = ['greu' => 'pff', 'another' => 'notpff'];

        $this->request = Request::createFromGlobals();
    }

    public function testGetGetParam()
    {
        $this->assertEquals('bar', $this->request->getGetParam('foo'));
        $this->assertEquals('default', $this->request->getGetParam('broo', 'default'));
        $this->assertNull($this->request->getGetParam('not'));
    }

    public function testGetPostParam()
    {
        $this->assertEquals('pff', $this->request->getPostParam('greu'));
        $this->assertEquals('default', $this->request->getPostParam('broo', 'default'));
        $this->assertNull($this->request->getPostParam('not'));
    }

    public function testIsGetParamValid()
    {
        $validator = $this->getBasicMock('horses\Validator');
        $validator->expects($this->any())
            ->method('isValid')
            ->will($this->returnCallback(function ($key) {return $key == 'bar';}));
        $this->assertTrue($this->request->isGetParamValid('foo', $validator));
        $this->assertFalse($this->request->isGetParamValid('other', $validator));
        $this->assertFalse($this->request->isGetParamValid('unknown', $validator));
    }

    public function testIsPostParamValid()
    {
        $validator = $this->getBasicMock('horses\Validator');
        $validator->expects($this->any())
            ->method('isValid')
            ->will($this->returnCallback(function ($key) {return $key == 'pff';}));
        $this->assertTrue($this->request->isPostParamValid('greu', $validator));
        $this->assertFalse($this->request->isPostParamValid('another', $validator));
        $this->assertFalse($this->request->isGetParamValid('unknown', $validator));
    }
}
