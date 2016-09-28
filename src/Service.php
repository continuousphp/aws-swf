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

    public function __construct(SwfClient $swfClient)
    {
        $this->swfClient = $swfClient;
        $this->domain = 'prod';
        $this->identify = 'UUID OF THE EC2';
    }

    /**
     * Poll a workflow entity for next decision request
     *
     * @return WorkflowInterface
     */
    public function pollWorkflow() : WorkflowInterface
    {
        $result = $this->swfClient->pollForDecisionTask([
            'domain' => $this->domain,
            'taskList' => [
                'name' => 'default',
            ],
            'identify' => $this->identify,
            'maximumPageSize' => 50,
            'reverseOrder' => true,
        ]);

        $workflowType = $result['workflowType'];
        $workflow = $this->getWorkflowEntity($workflowType);

        $workflow->hydrate($result['input']);
        $workflow->process($result['events']);

        return $workflow;
    }

    /**
     * @return \Generator of WorkflowInterface
     */
    public function pollWorkflowGenerator() : \Generator
    {
        while (true) {
            yield $this->pollWorkflow();
        }
    }

    /**
     * Poll a activity entity for next activity task
     *
     * @return ActivityInterface
     */
    public function pollActivity() : ActivityInterface
    {
        $result = $this->swfClient->pollForActivityTask([
            'domain' => $this->domain,
            'taskList' => [
                "name" => 'default'
            ]
        ]);

        $activityName = $result['activityType']['name'];
        $activity     = $this->getActivityEntity($activityName);

        $activity->hydrate($result['input']);

        return $activity;
    }

    /**
     * @return \Generator
     */
    public function pollActivityGenerator() : \Generator
    {
        while (true) {
            yield $this->pollActivity();
        }
    }

    /**
     * @param string $workflowType
     * @return WorkflowInterface
     */
    protected function getWorkflowEntity(string $workflowType) : WorkflowInterface
    {
    }

    /**
     * @param string $activityName
     * @return ActivityInterface
     */
    protected function getActivityEntity(string $activityName) : ActivityInterface
    {
    }
}
