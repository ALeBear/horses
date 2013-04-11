<?php

namespace horses\test;

include __DIR__ . '/AbstractTest.php';

use PHPUnit_Framework_TestCase;
use horses\Router;
use horses\Route;

class RouterTest extends AbstractTest
{
    /**
     * @var horses\Router
     */
    protected $router;
    
    
    protected function setUp()
    {
        parent::setUp();
        $this->router = new Router();
    }

    public function testRequestAttributes()
    {
        $request = $this->getRequest('/mod/act', array());
        $this->router->route($request);
        $this->assertEquals('mod', $request->attributes->get('MODULE'));
        $this->assertEquals('act', $request->attributes->get('ACTION'));
        $this->assertEquals('mod/act', $request->attributes->get('ROUTE'));
    }
    
    public function testDefaultRequestAttributes()
    {
        $request = $this->getRequest('/', array());
        $this->router->route($request);
        $this->assertEquals(Route::DEFAULT_MODULE, $request->attributes->get('MODULE'));
        $this->assertEquals(Route::DEFAULT_ACTION, $request->attributes->get('ACTION'));
        $this->assertEquals(Route::DEFAULT_MODULE . '/' . Route::DEFAULT_ACTION, $request->attributes->get('ROUTE'));

        $request = $this->getRequest('/' . Route::DEFAULT_MODULE, array());
        $this->router->route($request);
        $this->assertEquals(Route::DEFAULT_MODULE, $request->attributes->get('MODULE'));
        $this->assertEquals(Route::DEFAULT_ACTION, $request->attributes->get('ACTION'));
        $this->assertEquals(Route::DEFAULT_MODULE . '/' . Route::DEFAULT_ACTION, $request->attributes->get('ROUTE'));
    }
    
    public function testPrefix()
    {
        $this->router->route($this->getRequest('/', array()));
        $this->assertEquals('', $this->router->getPrefix());
        $this->router->addPrefix('pre');
        $this->assertEquals('/pre', $this->router->getPrefix());
        $this->router->addPrefix('fix');
        $this->assertEquals('/pre/fix', $this->router->getPrefix());
        $this->router->addPrefix('');
        $this->assertEquals('/pre/fix', $this->router->getPrefix());
        $this->router->addPrefix('0');
        $this->assertEquals('/pre/fix/0', $this->router->getPrefix());
        $this->router->addPrefix(null);
        $this->assertEquals('/pre/fix/0', $this->router->getPrefix());
    }
    
    public function testRoutePrefix()
    {
        $this->router->addPrefix('pre');
        
        $request = $this->getRequest('/mod/act', array());
        $this->router->route($request);
        $this->assertEquals('mod', $request->attributes->get('MODULE'));
        $this->assertEquals('act', $request->attributes->get('ACTION'));
        $this->assertEquals('mod/act', $request->attributes->get('ROUTE'));
        
        $request = $this->getRequest('/', array());
        $this->router->route($request);
        $this->assertEquals(Route::DEFAULT_MODULE, $request->attributes->get('MODULE'));
        $this->assertEquals(Route::DEFAULT_ACTION, $request->attributes->get('ACTION'));
        $this->assertEquals(Route::DEFAULT_MODULE . '/' . Route::DEFAULT_ACTION, $request->attributes->get('ROUTE'));

        $request = $this->getRequest('/pre/mod/act', array());
        $this->router->route($request);
        $this->assertEquals('mod', $request->attributes->get('MODULE'));
        $this->assertEquals('act', $request->attributes->get('ACTION'));
        $this->assertEquals('mod/act', $request->attributes->get('ROUTE'));
        
        $request = $this->getRequest('/pre', array());
        $this->router->route($request);
        $this->assertEquals(Route::DEFAULT_MODULE, $request->attributes->get('MODULE'));
        $this->assertEquals(Route::DEFAULT_ACTION, $request->attributes->get('ACTION'));
        $this->assertEquals(Route::DEFAULT_MODULE . '/' . Route::DEFAULT_ACTION, $request->attributes->get('ROUTE'));
    }
    
    public function testBuildRoute()
    {
        $this->router->route($this->getRequest('/', array()));
        $this->assertInstanceOf('horses\\Route', $this->router->buildRoute('/'));
    }
    
    public function testRequestQuery()
    {
        $request = $this->getRequest('/', array('this' => 'that'));
        $this->router->route($request);
        $this->assertEquals('that', $request->query->get('this'));
        $this->assertEquals(array('this' => 'that'), $request->query->all());
    }
}