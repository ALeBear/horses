<?php

namespace horses;

class Validator
{
    /** @var  integer */
    protected $id;
    /** @var array|callback */
    protected $options;

    /**
     * @param $id
     * @param array|callback $options
     */
    protected function __construct($id, $options = [])
    {
        $this->id = $id;
        $this->options = $options;
    }

    /**
     * @param mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        return filter_var($value, $this->id, $this->options) !== false;
    }

    /**
     * @return Validator
     */
    public static function booleanFactory()
    {
        return new self(FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return Validator
     */
    public static function emailFactory()
    {
        return new self(FILTER_VALIDATE_EMAIL);
    }

    /**
     * @param string $decimalSeparator
     * @return Validator
     */
    public static function floatFactory($decimalSeparator)
    {
        return new self(FILTER_VALIDATE_FLOAT, ['options' => ['decimal' => $decimalSeparator]]);
    }

    /**
     * @param integer $min
     * @param integer $max
     * @return Validator
     */
    public static function intFactory($min = null, $max = null)
    {
        $options = [];
        if ($min) {
            $options['min_range'] = $min;
        }
        if ($max) {
            $options['max_range'] = $max;
        }
        return new self(FILTER_VALIDATE_INT, ['options' => $options]);
    }

    /**
     * @param string $regexp
     * @return Validator
     */
    public static function regexpFactory($regexp)
    {
        return new self(FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => sprintf('/%s/', str_replace('/', '\/', $regexp))]]);
    }

    /**
     * @param callback $callback
     * @return Validator
     */
    public static function callbackFactory($callback)
    {
        return new self(FILTER_CALLBACK, ['options' => $callback]);
    }
}
