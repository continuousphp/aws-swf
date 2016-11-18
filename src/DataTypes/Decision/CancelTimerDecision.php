<?php

namespace Continuous\Swf\DataTypes\Decision;

use Continuous\Swf\Helper\ValueObject;

/**
 * Class DecisionTask
 * @package Continuous\Swf\Decider
 */
class CancelTimerDecision extends ValueObject implements DecisionInterface
{
    const DECISION_TYPE = 'CancelTimer';
    const ATTRIBUTE_KEY = 'cancelTimerDecisionAttributes';

    protected $timerId;

    /**
     * CancelTimerDecision constructor.
     * @param string $timerId
     */
    public function __construct(string $timerId)
    {
        $this->timerId = $timerId;
    }

    public function toRespondDecision() : array
    {
        return [
            'decisionType' => static::DECISION_TYPE,
            static::ATTRIBUTE_KEY => [
                'timerId' => $this->timerId,
            ],
        ];
    }

    public function getDecisionType() : string
    {
        return static::DECISION_TYPE;
    }

    public function getAttributeKey() : string
    {
        return static::ATTRIBUTE_KEY;
    }
}
