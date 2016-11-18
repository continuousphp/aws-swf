<?php

namespace Continuous\Swf\DataTypes\Decision;

/**
 * Interface DecisionInterface
 * @package Continuous\Swf\DataTypes\Decision
 */
interface DecisionInterface
{
    /**
     * The the decision type.
     *
     * one of
     *      | ScheduleActivityTask
     *      | RequestCancelActivityTask
     *      | CompleteWorkflowExecution
     *      | FailWorkflowExecution
     *      | CancelWorkflowExecution
     *      | ContinueAsNewWorkflowExecution
     *      | RecordMarker
     *      | StartTimer
     *      | CancelTimer
     *      | SignalExternalWorkflowExecution
     *      | RequestCancelExternalWorkflowExecution
     *      | StartChildWorkflowExecution
     *      | ScheduleLambdaFunction
     *
     * @return string
     */
    public function getDecisionType() : string;

    /**
     * @return string
     */
    public function getAttributeKey() : string;

    /**
     * Convert the object into array according to
     * respondDecisionTaskCompleted method expect.
     *
     * @return array
     */
    public function toRespondDecision() : array;
}
