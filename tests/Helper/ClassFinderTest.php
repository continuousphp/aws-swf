<?php

namespace Continuous\Tests\Swf\Helper;

use Continuous\Demo\Swf\BakingPasta\BakingPastaWorkflow;
use Continuous\Demo\Swf\Spaghetti\SpaghettiWorkflow;
use PHPUnit\Framework\TestCase;

class ClassFinderTest extends TestCase
{
    public function testFindClass()
    {
        $class = \Continuous\Swf\Helper\ClassFinder::findClass('Continuous\Demo\Swf', 'spaghetti', 'Workflow');
        $this->assertEquals(SpaghettiWorkflow::class, $class);

        $class = \Continuous\Swf\Helper\ClassFinder::findClass('Continuous\Demo\Swf', 'bakingpasta', 'Workflow');
        $this->assertEquals(BakingPastaWorkflow::class, $class);

        $class = \Continuous\Swf\Helper\ClassFinder::findClass(
            'Continuous\Demo\Swf',
            'bakingpasta.boilingwater',
            'Activity'
        );

        $this->assertEquals('Continuous\Demo\Swf\BakingPasta\BoilingWaterActivity', $class);
    }
}
