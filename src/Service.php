<?php

/**
 * Service.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      Service.php
 * @link      https://github.com/continuousphp/aws-swf the canonical source repo
 */
namespace Continuous\Swf;

use Aws\Swf\SwfClient;
use Continuous\Swf\Entity\Activity;
use Continuous\Swf\Entity\Workflow;

/**
 * Service
 *
 * @package   Continuous\Swf
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
class Service
{
    /**
     * @var SwfClient
     */
    protected $swfClient;

    /**
     * SWF Domain
     * @var string
     */
    protected $domain;

    /**
     * Decider entity
     * @var string
     */
    protected $identify;

    /**
     * Service constructor.
     * @param SwfClient $swfClient
     */
    public function __construct(SwfClient $swfClient)
    {
        $this->swfClient = $swfClient;
        $this->domain = 'prod';
        $this->identify = 'UUID OF THE EC2';
    }

    /**
     * Poll a workflow entity for next decision request
     *
     * @param string $taskList
     * @return WorkflowInterface
     */
    public function pollWorkflow(string $taskList = 'default') : WorkflowInterface
    {
        $result = $this->swfClient->pollForDecisionTask([
            'domain' => $this->domain,
            'taskList' => [
                'name' => $taskList,
            ],
            'identify' => $this->identify,
            'maximumPageSize' => 50,
            'reverseOrder' => true,
        ]);

        $workflowType = $result['workflowType'];
        $workflow = $this->getWorkflowEntity($workflowType);

        $workflow->hydrate($result['input'], $workflow);
        $workflow->process($result['events']);

        return $workflow;
    }

    /**
     * Generator of pollWorkflow.
     *
     * @param string $taskList
     * @return \Generator
     */
    public function pollWorkflowGenerator(string $taskList = 'default') : \Generator
    {
        while (true) {
            yield $this->pollWorkflow($taskList);
        }
    }

    /**
     * Poll a activity entity for next activity task.
     *
     * @param string $taskList
     * @return ActivityInterface
     */
    public function pollActivity(string $taskList = 'default') : ActivityInterface
    {
        $result = $this->swfClient->pollForActivityTask([
            'domain' => $this->domain,
            'taskList' => [
                "name" => $taskList
            ]
        ]);

        $activityName = $result['activityType']['name'];
        $activity     = $this->getActivityEntity($activityName);

        $activity->hydrate($result['input'], $activity);

        return $activity;
    }

    /**
     * Generator of pollActivity
     *
     * @param string $taskList
     * @return \Generator
     */
    public function pollActivityGenerator(string $taskList = 'default') : \Generator
    {
        while (true) {
            yield $this->pollActivity($taskList);
        }
    }

    /**
     * @param string $workflowType
     * @return Workflow
     */
    protected function getWorkflowEntity(string $workflowType) : Workflow
    {
    }

    /**
     * @param string $activityName
     * @return Activity
     */
    protected function getActivityEntity(string $activityName) : Activity
    {
    }
}
