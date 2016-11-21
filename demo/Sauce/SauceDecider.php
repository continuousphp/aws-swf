<?php

namespace Continuous\Demo\Swf\Sauce;

use Continuous\Swf\DataTypes\Decision\CompleteWorkflowExecutionDecision;
use Continuous\Swf\DataTypes\Decision\DecisionTrait;
use Continuous\Swf\DeciderInterface;

class SauceDecider extends SauceWorkflow implements DeciderInterface
{
    use DecisionTrait;

    protected $events;

    public function setEvents(array $events)
    {
        $this->events = $events;
    }

    public function process()
    {
        $this->addDecisionTask(new CompleteWorkflowExecutionDecision());
    }
}
