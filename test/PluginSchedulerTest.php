<?php

namespace horses\test;

use PHPUnit_Framework_TestCase;
use horses\PluginScheduler;

class PluginSchedulerTest extends PHPUnit_Framework_TestCase
{
    public function testDispatch()
    {
        $scheduler = new PluginScheduler();
        $this->assertEquals(array('main'), $scheduler->orderForDispatch(array()));
        $this->assertEquals(array('main'), $scheduler->orderForDispatch(array('main')));
        $this->assertEquals(array('auth', 'main'), $scheduler->orderForDispatch(array('auth')));
        $this->assertEquals(array('auth', 'main'), $scheduler->orderForDispatch(array('main', 'auth')));
        $this->assertEquals(array('auth', 'my\\last', 'main'), $scheduler->orderForDispatch(array('my\\last', 'main', 'auth')));
        $this->assertEquals(array('auth', 'my\\last', 'main'), $scheduler->orderForDispatch(array('main', 'my\\last', 'auth')));
        $this->assertEquals(array('auth', 'my\\last', 'main'), $scheduler->orderForDispatch(array('main', 'auth', 'my\\last')));
    }
    
    public function testBootstrap()
    {
        $scheduler = new PluginScheduler();
        $this->assertEquals(array('main'), $scheduler->orderForBootstrap(array()));
        $this->assertEquals(array('main'), $scheduler->orderForBootstrap(array('main')));
        $this->assertEquals(array('main', 'auth'), $scheduler->orderForBootstrap(array('auth')));
        $this->assertEquals(array('main', 'auth'), $scheduler->orderForBootstrap(array('main', 'auth')));
        $this->assertEquals(array('main', 'auth', 'my\\last'), $scheduler->orderForBootstrap(array('my\\last', 'main', 'auth')));
        $this->assertEquals(array('main', 'auth', 'my\\last'), $scheduler->orderForBootstrap(array('main', 'my\\last', 'auth')));
        $this->assertEquals(array('main', 'auth', 'my\\last'), $scheduler->orderForBootstrap(array('main', 'auth', 'my\\last')));
        $this->assertEquals(array('main', 'auth', 'doctrine', 'my\\last'), $scheduler->orderForBootstrap(array('doctrine', 'main', 'auth', 'my\\last')));
    }
}