<?php

namespace horses\test\mock;

use Symfony\Component\Config\IQueryableConfig;

class MockConfig  implements IQueryableConfig
{
    /**
     * @var array
     */
    protected $data;
    
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    public function get($name, $default = null)
    {
        return isset($this->data[$name]) ? $this->data[$name] : $default;
    }
    
    public function set($name, $value)
    {
        $this->data[$name] = $value;
        return $this;
    }
}