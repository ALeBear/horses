<?php

namespace horses\test;

use PHPUnit_Framework_TestCase;
use horses\View;

class ViewTest extends PHPUnit_Framework_TestCase
{
    public function testBasic()
    {
        $v = new View('myFile', 'myLayout');
        $this->assertEquals('myFile', $v->getFile());
        $this->assertEquals('myLayout', $v->getLayoutFile());
    }
}