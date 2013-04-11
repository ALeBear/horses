<?php

namespace horses\test;

use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractTest extends PHPUnit_Framework_TestCase
{
    /**
     * @param string $path
     * @param array $queryParameters
     * @return Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest($path, array $queryParameters)
    {
        return new Request($queryParameters, array(), array(), array(), array(), array(
            'SCRIPT_FILENAME' => '/dummy/index.php',
            'SCRIPT_NAME' => '/index.php',
            'PHP_SELF' => '/index.php',
            'REQUEST_URI' => $path));
    }
}