<?php

namespace Continuous\Tests\Swf\Helper;

use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testGetAll()
    {
        $configurations = \Continuous\Swf\Helper\Config::getAll();

        $this->assertInstanceOf(\stdClass::class, $configurations);

        $this->assertObjectHasAttribute('aws', $configurations);
        $this->assertObjectHasAttribute('identity', $configurations);
        $this->assertObjectHasAttribute('swf', $configurations);
    }

    public function testGetSection()
    {
        $aws = \Continuous\Swf\Helper\Config::getSection('aws');

        $this->assertInstanceOf(\stdClass::class, $aws);
        $this->assertObjectHasAttribute('region', $aws);
    }
}
