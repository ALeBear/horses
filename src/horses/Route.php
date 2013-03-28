<?php

namespace horses;

use InvalidArgumentException;

/**
 * Very simple routing class. 
 */
class Route
{
    const DEFAULT_MODULE = 'defaulter';
    const DEFAULT_ACTION = 'index';
    
    /**
     * @var string
     */
    protected $route;
    
    /**
     * @var array
     */
    protected $query;
    
    /**
     * @var array
     */
    protected $options;
    

    /**
     * @param string $route The "module/action" combo in one string
     * @param array $query Query string parameters, will be in the url itself
     * @param array $options
     * @throws \InvalidArgumentException
     */
    public function __construct($route, array $query = array(), array $options = array())
    {
        if (strpos($route, '/') === false) {
            throw new InvalidArgumentException(sprintf('Route must contain a /, not: %s', $route));
        }
        $this->route = $route;
        $this->query = $query;
        $this->options = $options;
    }
    
    /**
     * @return string
     */
    public function getUrl()
    {
        list($module, $action) = explode('/', $this->route, 2);
        $prefix = isset($this->options['prefix']) ? '/' . trim($this->options['prefix'], '/') : '';
        $rootPath = sprintf('%s/%s', $prefix, self::DEFAULT_MODULE);
        $path = sprintf('%s/%s', $prefix, $module ?: self::DEFAULT_MODULE);
        
        //Add action only if not the default and no query params
        if (($action && $action != self::DEFAULT_ACTION) || count($this->query)) {
            $path .= '/' . ($action ?: self::DEFAULT_ACTION);
            foreach ($this->query as $name => $value) {
                $path .= sprintf('/%s/%s', $name, urlencode($value));
            }
        }
        
        //Remove default module
        if ($path == $rootPath) {
            $path = $prefix;
        }
        
        if (isset($this->options['absolute']) && $this->options['absolute']) {
            //TODO
        }
        
        return $path;
    }
    
    /**
     * @param string[] $options
     * @return \horses\Route
     */
    public function addOptions(array $options)
    {
        $this->options = array_merge($this->options, $options);
        return $this;
    }
}
