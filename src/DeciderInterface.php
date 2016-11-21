<?php

namespace Continuous\Swf;

use Continuous\Swf\Entity\WorkflowInterface;

interface DeciderInterface extends WorkflowInterface
{
    public function setTaskToken(string $taskToken);
    public function getTaskToken() : string;
    public function setEvents(array $events);
    public function process();
}
