<?php

namespace horses\test\mock;

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
        return isset($data[$name]) ? $data[$name] : $default;
    }
    
    public function set($name, $value)
    {
        $this->data[$name] = $value;
        return $this;
    }
}