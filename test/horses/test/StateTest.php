<?php

namespace horses\test;

use horses\State;
use Symfony\Component\HttpFoundation\Session\Session;

class StateTest extends AbstractTest
{
    /** @var State */
    protected $state;
    /** @var array */
    protected $storedValues = [];
    /** @var Session */
    protected $session = [];

    
    protected function setUp()
    {
        parent::setUp();
        $this->session = $this->getBasicMock('\Symfony\Component\HttpFoundation\Session\Session');
        $sv =& $this->storedValues;
        $this->session->expects($this->any())
            ->method('set')
            ->will($this->returnCallback(function ($key, $value) use (&$sv) {$sv[$key] = $value;}));
        $this->state = new State($this->session);
    }

    public function testGetUserId()
    {
        $this->session->expects($this->any())
            ->method('get')
            ->will($this->returnValueMap([
                [State::USER_KEY, null, 'bar']
            ]));
        $this->assertEquals('bar', $this->state->getUserId()->getId());
    }

    public function testGetUserIdNone()
    {
        $this->session->expects($this->any())
            ->method('get')
            ->will($this->returnValueMap([
                [State::USER_KEY, null, null]
            ]));
        $this->assertNull($this->state->getUserId());
    }

    public function testSaveUserId()
    {
        $this->session->expects($this->any())
            ->method('get')
            ->will($this->returnValueMap([
                [State::USER_KEY, null, 'bar']
            ]));
        $this->state->saveUserId($this->state->getUserId());
        $this->assertEquals('bar', $this->storedValues[State::USER_KEY]);
    }

    public function testDeleteUserId()
    {
        $this->session->expects($this->any())
            ->method('get')
            ->will($this->returnValueMap([
                [State::USER_KEY, null, 'bar']
            ]));
        $this->state->saveUserId($this->state->getUserId());
        $this->assertEquals('bar', $this->storedValues[State::USER_KEY]);
        $this->state->deleteUserId();
        $this->assertNull($this->storedValues[State::USER_KEY]);
    }
}
