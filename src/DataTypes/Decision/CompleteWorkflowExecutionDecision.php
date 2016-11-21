<?php

namespace Continuous\Swf\DataTypes\Decision;

use Continuous\Swf\Helper\ValueObject;

/**
 * Class CompleteWorkflowExecutionDecision
 * @package Continuous\Swf\DataTypes\Decision
 */
class CompleteWorkflowExecutionDecision extends ValueObject implements DecisionInterface
{
    const DECISION_TYPE = 'CompleteWorkflowExecution';
    const ATTRIBUTE_KEY = 'completeWorkflowExecutionDecisionAttributes';

    protected $result;

    /**
     * CancelTimerDecision constructor.
     * @param string $timerId
     */
    public function __construct(string $result = null)
    {
        if (null !== $result && ( 32768 < strlen($result) || 1 > strlen($result) )) {
            throw new DecisionException('result must be string greater than 0 and less than 32768 characters');
        }

        $this->result = $result;
    }

    public function toRespondDecision() : array
    {
        $response = [
            'decisionType' => static::DECISION_TYPE,
        ];

        if (null !== $this->result) {
            $response[static::ATTRIBUTE_KEY] = [
                'result' => $this->result,
            ];
        }

        return $response;
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
