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

    public function extract() : array
    {
        return [];
    }

    public function hydrate(array $data)
    {
    }

    public function process()
    {
        $this->completed();
    }
}
