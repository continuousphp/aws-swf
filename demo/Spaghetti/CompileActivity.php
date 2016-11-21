<?php

namespace Continuous\Demo\Swf\Spaghetti;

use Continuous\Swf\Entity\Activity;

class CompileActivity extends Activity
{
    const NAME = 'spaghetti.compile';
    const VERSION = '0.1.0';

    public function getName() : string
    {
        return static::NAME;
    }

    public function getVersion() : string
    {
        return static::VERSION;
    }


    public function setResult()
    {
        // TODO: Implement setResult() method.
    }

    public function extract() : array
    {
        // TODO: Implement extract() method.
    }

    public function hydrate(array $data)
    {
        // TODO: Implement hydrate() method.
    }
}
