<?php

namespace Continuous\Swf\DataTypes\Decision;

/**
 * Class DecisionTrait
 * @package Continuous\Swf\DataTypes\Decision
 */
trait DecisionTrait
{
    /**
     * @var string token
     */
    protected $taskToken;

    /**
     * @var DecisionInterface[]
     */
    protected $decisionTasksList = [];

    /**
     * @param string $taskToken
     * @return $this
     */
    public function setTaskToken(string $taskToken)
    {
        $this->taskToken = $taskToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getTaskToken() : string
    {
        return $this->taskToken;
    }

    /**
     * @param DecisionInterface $decision
     */
    public function addDecisionTask(DecisionInterface $decision)
    {
        $this->decisionTasksList[] = $decision;
        return $this;
    }

    /**
     * @return DecisionInterface[]
     */
    public function getDecisionTasksList()
    {
        return $this->decisionTasksList;
    }

    /**
     * Return true if we have decisionsTask to respond
     *
     * @return bool
     */
    public function hasDecisions()
    {
        return 0 < count($this->decisionTasksList);
    }

    /**
     * @param array $events
     * @param array $eventType
     * @param $callable
     */
    public function filter(array $events, array $eventType, $callable = null)
    {
        $result = [];

        foreach ($events as $event) {
            if (false === in_array($event['eventType'], $eventType)) {
                continue;
            }

            if (null === $callable) {
                $result[] = $event;
                continue;
            }

            if (true === call_user_func($callable, $event)) {
                $result[] = $event;
            }
        }

        return $result;
    }
}
