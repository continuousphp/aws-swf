<?php

namespace Continuous\Swf\DataTypes\Decision;

/**
 * Class DecisionTrait
 * @package Continuous\Swf\DataTypes\Decision
 */
trait DecisionTrait
{
    /**
     * @var DecisionTask[]
     */
    protected $decisionTasksList = [];

    /**
     * @param DecisionTask $decisionTask
     */
    protected function addDecisionTask(DecisionInterface $decision)
    {
        $this->$decisionTasksList[] = $decision;
    }
}
