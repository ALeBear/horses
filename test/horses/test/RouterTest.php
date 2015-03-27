<?php

namespace horses\test\config;

use horses\test\AbstractTest;
use horses\Router;

class RouterTest extends AbstractTest
{
    /** @var Router */
    protected $router;
    /** @var string */
    protected $routePrefix = '';
    /** @var string */
    protected $actionNamespace = 'horses\test\stub';

    
    protected function setUp()
    {
        parent::setUp();
        $serverContext = $this->getBasicMock('\horses\ServerContext');
        $serverContext->expects($this->any())
            ->method('set')
            ->will($this->returnCallback(function ($key, $value) use (&$sv) {$sv[$key] = $value;}));

        $config = $this->getBasicMock('\horses\config\Config');
        $config->expects($this->any())
            ->method('get')
            ->will($this->returnCallback(function ($key, $default) { $vals = [
                Router::CONFIG_KEY_PREFIX => $this->routePrefix,
                Router::CONFIG_KEY_ACTION_NAMESPACE => $this->actionNamespace
            ]; return $vals[$key];}));
        $configCollection = $this->getBasicMock('\horses\config\Collection');
        $configCollection->expects($this->any())
            ->method('getSection')
            ->will($this->returnValue($config));
        $configCollection->expects($this->any())
            ->method('load')
            ->will($this->returnSelf());

        $this->router = new Router($serverContext, $configCollection);
    }

    public function testUcWordize()
    {
        $this->assertEquals('ThisIsCool', Router::wordize('tHIS-is-COOL'));
        $this->assertEquals('', Router::wordize('-'));
        $this->assertEquals('', Router::wordize(null));
    }

    public function testDashize()
    {
        $this->assertEquals('this-is-cool', Router::dashize('ThisIsCool'));
        $this->assertEquals('', Router::dashize(''));
        $this->assertEquals('', Router::dashize(null));
    }

    public function testRoute()
    {
        $request = $this->getBasicMock('\Symfony\Component\HttpFoundation\Request');
        $request->query = $this->getBasicMock('\Symfony\Component\HttpFoundation\ParameterBag');
        $request->expects($this->any())
            ->method('getPathInfo')
            ->will($this->returnValue('/three-words-action'));
        $this->assertInstanceOf('horses\test\stub\ThreeWordsAction', $this->router->route($request));
    }

    /**
     * @expectedException \horses\UnknownRouteException
     */
    public function testRouteNoActionNamespace()
    {
        $request = $this->getBasicMock('\Symfony\Component\HttpFoundation\Request');
        $request->query = $this->getBasicMock('\Symfony\Component\HttpFoundation\ParameterBag');
        $request->expects($this->any())
            ->method('getPathInfo')
            ->will($this->returnValue('/three-words-action'));
        $this->actionNamespace = null;
        $this->assertInstanceOf('horses\test\stub\ThreeWordsAction', $this->router->route($request));
    }

    public function testRouteWithPrefix()
    {
        $request = $this->getBasicMock('\Symfony\Component\HttpFoundation\Request');
        $request->query = $this->getBasicMock('\Symfony\Component\HttpFoundation\ParameterBag');
        $request->expects($this->any())
            ->method('getPathInfo')
            ->will($this->returnValue('/prefix/three-words-action'));
        $this->routePrefix = 'prefix';
        $this->assertInstanceOf('horses\test\stub\ThreeWordsAction', $this->router->route($request));
    }

    public function testRouteWithParams()
    {
        $request = $this->getBasicMock('\Symfony\Component\HttpFoundation\Request');
        $request->query = $this->getBasicMock('\Symfony\Component\HttpFoundation\ParameterBag');
        $params = [];
        $request->query->expects($this->any())
            ->method('add')
            ->will($this->returnCallback(function ($newparams) use (&$params) { $params = $newparams; }));
        $request->expects($this->any())
            ->method('getPathInfo')
            ->will($this->returnValue('/three-words-action/param1/value1/param2/value2/param3'));
        $this->assertInstanceOf('horses\test\stub\ThreeWordsAction', $this->router->route($request));
        $this->assertEquals(['param1' => 'value1', 'param2' => 'value2', 'param3' => null], $params);
    }

    /**
     * @expectedException \horses\UnknownRouteException
     */
    public function testRouteUnknown()
    {
        $request = $this->getBasicMock('\Symfony\Component\HttpFoundation\Request');
        $request->query = $this->getBasicMock('\Symfony\Component\HttpFoundation\ParameterBag');
        $request->expects($this->any())
            ->method('getPathInfo')
            ->will($this->returnValue('/unknown'));
        $this->router->route($request);
    }
}