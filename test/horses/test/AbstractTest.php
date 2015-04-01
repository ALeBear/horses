<?php

namespace horses\test;

use PHPUnit_Framework_TestCase;
use horses\Request;
use PHPUnit_Framework_MockObject_MockObject;

abstract class AbstractTest extends PHPUnit_Framework_TestCase
{
    /**
     * Returns a basic mock with constructor disabled for the given class
     * @param $className
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    public function getBasicMock($className)
    {
        return $this->getMockBuilder($className)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @param string $path
     * @param array $queryParameters
     * @return \horses\Request
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
