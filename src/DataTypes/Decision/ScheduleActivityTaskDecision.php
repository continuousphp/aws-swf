<?php

namespace Continuous\Swf\DataTypes\Decision;

use Continuous\Swf\Helper\ValueObject;

/**
 * Class ScheduleActivityTaskDecision
 * @package Continuous\Swf\DataTypes\Decision
 */
class ScheduleActivityTaskDecision extends ValueObject implements DecisionInterface
{
    const DECISION_TYPE = 'ScheduleActivityTask';
    const ATTRIBUTE_KEY = 'scheduleActivityTaskDecisionAttributes';

    protected $activityId;
    protected $activityType;
    protected $control;
    protected $heartbeatTimeout;
    protected $scheduleToCloseTimeout;
    protected $scheduleToStartTimeout;
    protected $startToCloseTimeout;
    protected $taskList;
    protected $taskPriority;

    public function __construct(
        string $activityId,
        array $activityType,
        string $control = '',
        $heartbeatTimeout = -1,
        $scheduleToCloseTimeout = -1,
        $scheduleToStartTimeout = -1,
        $startToCloseTimeout = -1,
        array $taskList = null,
        int $taskPriority = 0
    ) {
        $this->activityId = $activityId;

        if (2 !== count($activityType) || empty($activityType['name']) || empty($activityType['version'])) {
            new DecisionException('activityType argument must be match to AWS-SWF activity type structure.');
        }

        $this->activityType = $activityType;

        if ('' !== $control) {
            $this->control = $control;
        }

        if (-1 !== $heartbeatTimeout && 'NONE' !== $heartbeatTimeout && 0 > (int)$heartbeatTimeout) {
            new DecisionException('heartbeatTimeout must be greater than 0 or string NONE.');
        }

        $this->heartbeatTimeout = $heartbeatTimeout;

        if (-1 !== $scheduleToCloseTimeout && 'NONE' !== $scheduleToCloseTimeout && 0 > (int)$scheduleToCloseTimeout) {
            new DecisionException('scheduleToCloseTimeout must be greater than 0 or string NONE.');
        }

        $this->scheduleToCloseTimeout = $scheduleToCloseTimeout;

        if (-1 !== $scheduleToStartTimeout && 'NONE' !== $scheduleToStartTimeout && 0 > (int)$scheduleToStartTimeout) {
            new DecisionException('scheduleToStartTimeout must be greater than 0 or string NONE.');
        }

        $this->scheduleToStartTimeout = $scheduleToStartTimeout;

        if (-1 !== $startToCloseTimeout && 'NONE' !== $startToCloseTimeout && 0 > (int)$startToCloseTimeout) {
            new DecisionException('startToCloseTimeout must be greater than 0 or string NONE.');
        }

        $this->startToCloseTimeout = $startToCloseTimeout;

        if (null !== $taskList && 1 !== count($taskList) || empty($taskList['name'])) {
            new DecisionException('Tasklist argument must be match to AWS-SWF taskList structure.');
        }

        $this->taskList = $taskList;

        if (-2147483648 > $taskPriority || 2147483647 < $taskPriority) {
            new DecisionException('taskPriority must be an integer between -2147483648 and 2147483647');
        }

        $this->taskPriority = $taskPriority;
    }

    public function toRespondDecision() : array
    {
        $attributes = [
            'activityId' => $this->activityId,
            'activityType' => $this->activityType,
        ];

        if (null !== $this->control) {
            $attributes['control'] = $this->control;
        }

        if (-1 !== $this->heartbeatTimeout) {
            $attributes['heartbeatTimeout'] = $this->heartbeatTimeout;
        }

        if (-1 !== $this->scheduleToCloseTimeout) {
            $attributes['scheduleToCloseTimeout'] = $this->scheduleToCloseTimeout;
        }

        if (-1 !== $this->scheduleToStartTimeout) {
            $attributes['scheduleToStartTimeout'] = $this->scheduleToStartTimeout;
        }

        if (-1 !== $this->startToCloseTimeout) {
            $attributes['startToCloseTimeout'] = $this->startToCloseTimeout;
        }

        if (null !== $this->taskList) {
            $attributes['taskList'] = $this->taskList;
        }

        if (0 !== $this->taskPriority) {
            $attributes['taskPriority'] = $this->taskPriority;
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
