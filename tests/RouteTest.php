<?php

namespace horses\tests;

use PHPUnit_Framework_TestCase;
use horses\Route;

class RouteTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
    public function testNoSlashInRoute()
    {
        $r = new Route('');
    }
    
    public function testIndex()
    {
        $r = new Route('/');
        $this->assertEquals('/', $r->getUrl());
    }
    
    public function testModuleIndex()
    {
        $r = new Route('foo/');
        $this->assertEquals('/foo', $r->getUrl());
    }
    
    public function testDefaultModuleIndex()
    {
        $r = new Route('defaulter/');
        $this->assertEquals('/', $r->getUrl());
    }
    
    public function testDefaultModuleIndexWithParam()
    {
        $r = new Route('foo/', array('second' => 'ary'));
        $this->assertEquals('/foo/index/second/ary', $r->getUrl());
    }
    
    public function testDefaultModuleExplicitIndex()
    {
        $r = new Route('foo/index');
        $this->assertEquals('/foo', $r->getUrl());
    }
    
    public function testDefaultModuleAction()
    {
        $r = new Route('defaulter/greu');
        $this->assertEquals('/defaulter/greu', $r->getUrl());
    }
    
    public function testBasicRoute()
    {
        $r = new Route('foo/bar');
        $this->assertEquals('/foo/bar', $r->getUrl());
    }
    
    public function testBasicRouteWithQuery()
    {
        $r = new Route('foo/bar', array('greu' => 'moo'));
        $this->assertEquals('/foo/bar/greu/moo', $r->getUrl());
    }
    
    public function testBasicRouteWithTwoQuery()
    {
        $r = new Route('foo/bar', array('greu' => 'moo', 'alpha' => 'female'));
        $this->assertEquals('/foo/bar/greu/moo/alpha/female', $r->getUrl());
    }
    
    public function testUrlencode()
    {
        $r = new Route('foo/bar', array('greu' => 'moo then'));
        $this->assertEquals('/foo/bar/greu/moo+then', $r->getUrl());
    }
    
    public function testPrefix()
    {
        $r = new Route('foo/bar', array(), array('prefix' => 'pfx'));
        $this->assertEquals('/pfx/foo/bar', $r->getUrl());
    }
    
    public function testPrefixAndQuery()
    {
        $r = new Route('foo/bar', array('moo' => 'yes'), array('prefix' => 'pfx'));
        $this->assertEquals('/pfx/foo/bar/moo/yes', $r->getUrl());
    }
    
    public function testAddOptions()
    {
        $r = new Route('foo/bar');
        $r->addOptions(array('prefix' => 'pfx'));
        $this->assertEquals('/pfx/foo/bar', $r->getUrl());
    }
    
    public function testAddInconsequentialOptions()
    {
        $r = new Route('foo/bar');
        $r->addOptions(array('myoption' => 'pfx'));
        $this->assertEquals('/foo/bar', $r->getUrl());
    }
}