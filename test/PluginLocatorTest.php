<?php

namespace horses\test;

include_once __DIR__ . '/mock/MockPlugin.php';

use PHPUnit_Framework_TestCase;
use horses\PluginLocator;

class PluginLocatorTest extends PHPUnit_Framework_TestCase
{
    public function testLocate()
    {
        $locator = new PluginLocator();
        $this->assertInstanceOf('horses\\plugin\\main\\Plugin', $locator->locate('main'));
        $this->assertInstanceOf('horses\\plugin\\doctrine\\Plugin', $locator->locate('doctrine'));
        $this->assertInstanceOf('horses\\test\\mock', $locator->locate('horses\\test\\mock'));
    }
}