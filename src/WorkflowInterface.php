<?php

namespace Continuous\Swf;

use Aws\Result;

/**
 * Interface Workflow
 */
interface WorkflowInterface
{
    /**
     * @param Result $result
     * @return mixed
     */
    public function process(Result $result);

    public function setResult();
}
