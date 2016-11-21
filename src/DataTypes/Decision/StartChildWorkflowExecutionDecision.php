<?php

namespace Continuous\Swf\DataTypes\Decision;

use Continuous\Swf\Entity\Workflow;
use Continuous\Swf\Helper\ValueObject;
use Ramsey\Uuid\Uuid;

/**
 * Class StartChildWorkflowExecutionDecision
 * @package Continuous\Swf\DataTypes\Decision
 */
class StartChildWorkflowExecutionDecision extends ValueObject implements DecisionInterface
{
    const DECISION_TYPE = 'StartChildWorkflowExecution';
    const ATTRIBUTE_KEY = 'startChildWorkflowExecutionDecisionAttributes';

    /**
     * @var Workflow
     */
    protected $workflow;
    protected $taskList;
    protected $childPolicy;
    protected $control;
    protected $executionStartToCloseTimeout;
    protected $lambdaRoleArn;
    protected $tagList;
    protected $taskPriority;
    protected $taskStartToCloseTimeout;

    public function __construct(
        Workflow $workflow,
        array $taskList,
        string $childPolicy = '',
        string $control = '',
        int $executionStartToCloseTimeout = -1,
        string $lambdaRoleArn = '',
        array $tagList = [],
        int $taskPriority = 0,
        $taskStartToCloseTimeout = -1
    ) {
        $this->workflow = $workflow;

        if (1 !== count($taskList) || empty($taskList['name'])) {
            new DecisionException('Tasklist argument must be match to AWS-SWF taskList structure.');
        }

        $this->taskList = $taskList;

        if ('' !== $childPolicy
            && false == in_array($childPolicy, ['TERMINATE', 'REQUEST_CANCEL', 'ABANDON'])
        ) {
            new DecisionException('childPolicy must be one of TERMINATE, REQUEST_CANCEL, ABANDON.');
        }

        $this->childPolicy = $childPolicy;

        if ('' !== $control) {
            $this->control = $control;
        }

        if (-1 !== $executionStartToCloseTimeout && 0 > $executionStartToCloseTimeout) {
            new DecisionException('executionStartToCloseTimeout must be greater than 0.');
        }

        $this->executionStartToCloseTimeout = $executionStartToCloseTimeout;

        if ('' !== $lambdaRoleArn) {
            $this->lambdaRoleArn = $lambdaRoleArn;
        }

        if (5 < count($tagList)) {
            new DecisionException('You can specify only maximum 5 tags on tagList argument');
        }

        $this->tagList = $tagList;

        if (-2147483648 > $taskPriority || 2147483647 < $taskPriority) {
            new DecisionException('taskPriority must be an integer between -2147483648 and 2147483647');
        }

        $this->taskPriority = $taskPriority;

        if (-1 !== $taskStartToCloseTimeout
            && 'NONE' !== $taskStartToCloseTimeout
            && 0 > (int)$taskStartToCloseTimeout
        ) {
            new DecisionException('taskStartToCloseTimeout must be greater than 0 or string NONE.');
        }

        $this->taskStartToCloseTimeout = $taskStartToCloseTimeout;
    }

    public function toRespondDecision() : array
    {
        $id = $this->workflow->getId();

        if (false === Uuid::isValid($id)) {
            throw new \Exception('Workflow must have ID defined. ', get_class($this->workflow));
        }

        $attributes = [
            'taskList'     => $this->taskList,
            'workflowId'   => $id,
            'workflowType' => [
                'name'    => $this->workflow->getName(),
                'version' => $this->workflow->getVersion(),
            ],
            'input' => json_encode($this->workflow->extract())
        ];

        if ('' !== $this->childPolicy) {
            $attributes['childPolicy'] = $this->childPolicy;
        }

        if (null !== $this->control) {
            $attributes['control'] = $this->control;
        }

        if (-1 !== $this->executionStartToCloseTimeout) {
            $attributes['executionStartToCloseTimeout'] = $this->executionStartToCloseTimeout;
        }

        if (null !== $this->lambdaRoleArn) {
            $attributes['lambdaRole'] = $this->lambdaRoleArn;
        }

        if (!empty($this->tagList)) {
            $attributes['tagList'] = $this->tagList;
        }

        if (0 !== $this->taskPriority) {
            $attributes['taskPriority'] = $this->taskPriority;
        }

        if (-1 !== $this->taskStartToCloseTimeout) {
            $attributes['taskStartToCloseTimeout'] = $this->taskStartToCloseTimeout;
        }

        return [
            'decisionType' => static::DECISION_TYPE,
            static::ATTRIBUTE_KEY => $attributes,
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
