<?php

namespace Continuous\Demo\Swf\BakingPasta;

use Continuous\Swf\DataTypes\Decision\CompleteWorkflowExecutionDecision;
use Continuous\Swf\DataTypes\Decision\DecisionTrait;
use Continuous\Swf\DeciderInterface;

/**
 * Class BakingPastaDecider
 * @package Continuous\Demo\Swf\BakingPasta
 */
class BakingPastaDecider extends BakingPastaWorkflow implements DeciderInterface
{
    use DecisionTrait;

    protected $events;

    /**
     * @param array $events
     */
    public function setEvents(array $events)
    {
        $this->events = $events;
    }

    /**
     * Process event for schedule decisions
     */
    public function process()
    {
        $this->addDecisionTask(new CompleteWorkflowExecutionDecision());
    }
}
