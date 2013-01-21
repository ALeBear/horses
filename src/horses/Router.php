<?php

namespace horses;

use Symfony\Component\HttpFoundation\Request;

/**
 * Very simple routing class. 
 * Options recognized by buildRoute:
 * - absolute
 */
class Router
{
    /**
     * @var string[]
     */
    protected $prefixes = array();

    /**
     * Do the routing, i.e. set ROUTE/MODULE/ACTION request attribute
     * (ROUTE = "controller/action") and reinject parameters passed in path
     * inside request
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \horses\Router $this
     */
    public function route(Request $request)
    {
        $params = array();
        $parts = explode('/', rtrim($request->getPathInfo(), '/'));
        array_shift($parts);
        foreach ($this->prefixes as $prefix) {
            count($parts ) && $parts[0] == $prefix && array_shift($parts);
        }
        $module = count($parts) ? array_shift($parts) : Route::DEFAULT_MODULE;
        $action = (count($parts) ? array_shift($parts) : Route::DEFAULT_ACTION);
        while (count($parts)) {
            $params[array_shift($parts)] = count($parts) ? array_shift($parts) : null;
        }
        $request->attributes->set('MODULE', $module);
        $request->attributes->set('ACTION', $action);
        $request->attributes->set('ROUTE', sprintf('%s/%s', $module, $action));
        $request->query->add($params);

        return $this;
    }

    /**
     * @param string $prefix
     */
    public function addPrefix($prefix)
    {
        $this->prefixes[] = $prefix;
    }

    /**
     * @param string $route The "module/action" combo in one string
     * @param array $query Query string parameters, will be in the url itself
     * @param array $options
     * @return \horses\Route
     */
    public function buildRoute($route, array $query = array(), array $options = array())
    {
        return new Route($route, $query, array_merge($options, array('prefix' => "/" . $this->getPrefix())));
    }
    
    /**
     * @param \horses\Route $route
     */
    public static function redirect(Route $route)
    {
        self::redirectExternal($route->getUrl());
    }
    
    /**
     * @param string $url
     */
    public static function redirectExternal($url)
    {
        header(sprintf('Location: %s', $url));
        exit;
    }
    
    /**
     * @return string
     */
    public function getPrefix()
    {
        return implode('/', $this->prefixes);
    }
}
