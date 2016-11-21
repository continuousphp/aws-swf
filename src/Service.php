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

use Aws\Result;
use Aws\Swf\SwfClient;
use Continuous\Swf\DataTypes\Decision\DecisionTrait;
use Continuous\Swf\Entity\Activity;
use Continuous\Swf\Entity\ActivityInterface;
use Continuous\Swf\Entity\Workflow;
use Continuous\Swf\Helper\ClassFinder;

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
     * @var ServiceConfig
     */
    protected $config;

    /**
     * Service constructor.
     * @param SwfClient $swfClient
     */
    public function __construct(ServiceConfig $serviceConfig)
    {
        $this->config    = $serviceConfig;
        $this->swfClient = $serviceConfig->swfClient;

        if (false === file_exists(dirname(__DIR__)
            . DIRECTORY_SEPARATOR
            . 'vendor'
            . DIRECTORY_SEPARATOR
            . 'composer'
            . DIRECTORY_SEPARATOR
            . 'autoload_classmap.php')
        ) {
            throw new ServiceException('You must generate composer autoload_classmap file 
            for be able to reference your namepsace class without camelCase issues');
        }
    }

    /**
     * Poll a workflow decider
     *
     * @param string $taskList
     */
    public function pollWorkflow(string $taskList = 'default') : DeciderInterface
    {
        $result = $this->swfClient->pollForDecisionTask([
            'domain' => $this->config->domain,
            'taskList' => [
                'name' => $taskList,
            ],
            'identify' => $this->config->identity,
            'reverseOrder' => false,
        ]);

        //TODO poll with nextPageToken if max number of events is reach

        if (empty($result['events'])) {
            var_dump($result);
            throw new ServiceException('No events detect on decision request.');
        }

        foreach ($result['events'] as $event) {
            if ('WorkflowExecutionStarted' !== $event['eventType']) {
                continue;
            }

            $workflowType = $event['workflowExecutionStartedEventAttributes']['workflowType'];
            $decider = $this->getDeciderEntity($workflowType['name']);
            $decider->setId($result['workflowExecution']['workflowId']);
            $decider->setTaskToken($result['taskToken']);
            $decider->hydrate(json_decode($event['workflowExecutionStartedEventAttributes']['input'], true));

            break;
        }

        $decider->setEvents(array_reverse($result['events']));

        return $decider;
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
    public function pollActivity(string $taskList = 'default') : Activity
    {
        $result = $this->swfClient->pollForActivityTask([
            'domain' => $this->config->domain,
            'taskList' => [
                "name" => $taskList
            ]
        ]);

        //TODO if nothing to treat...

        eval(\Psy\sh());

        $activityName = $result['activityType']['name'];
        $activity     = $this->getActivityEntity($activityName);

        $activity->setId($result['activityId']);
        $activity->setTaskToken($result['taskToken']);
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
     * @param string $deciderName
     * @param string $version
     * @return DeciderInterface
     */
    protected function getDeciderEntity(string $deciderName) : DeciderInterface
    {
        $className = ClassFinder::findClass($this->config->namespace, $deciderName, 'Decider');

        if (null === $className) {
            throw new ServiceException('Decider entity not found for ' . $deciderName);
        }

        return new $className();
    }

    /**
     * @param string $name
     * @param $version
     * @return Workflow
     */
    protected function getWorkflowEntity(string $workflowName, string $version) : Workflow
    {
        $className = ClassFinder::findClass($this->config->namespace, $workflowName, 'Workflow');

        if (null === $className) {
            throw new ServiceException('Workflow entity not found for ' . $workflowName);
        }

        return new $className();
    }

    /**
     * @param string $activityName
     * @return Activity
     */
    protected function getActivityEntity(string $activityName) : Activity
    {
        $className = ClassFinder::findClass($this->config->namespace, $activityName, 'Activity');

        if (null === $className) {
            throw new ServiceException('Activity entity not found for ' . $activityName);
        }

        return new $className();
    }

    /**
     * Send RespondDecisionTask to SWF API.
     *
     * @param DeciderInterface $decider
     */
    public function respondDecisionTaskCompleted(DeciderInterface $decider)
    {
        $decisions = [];

        foreach ($decider->getDecisionTasksList() as $decision) {
            $decisions[] = $decision->toRespondDecision();
        }

        if (empty($decisions)) {
            return;
        }

        $this->swfClient->respondDecisionTaskCompleted([
            'taskToken' => $decider->getTaskToken(),
            'decisions' => $decisions,
        ]);
    }

    public function respondActivityTaskCompleted(ActivityInterface $activity, string $detail = '', string $reason = '')
    {
        $status = $activity->getStatus();

        if (Activity::COMPLETED === $status) {
            $this->swfClient->respondActivityTaskCompleted([
                'taskToken' => $activity->getTaskToken(),
                'result'    => json_encode($activity->extract()),
            ]);

            return;
        }

        if (Activity::CANCELED === $status) {
            $this->swfClient->respondActivityTaskCanceled([
                'taskToken' => $activity->getTaskToken(),
                'detail' => $detail,
            ]);

            return;
        }

        if (Activity::FAILED === $status) {
            $this->swfClient->respondActivityTaskFailed([
                'taskToken' => $activity->getTaskToken(),
                'detail' => $detail,
                'reason' => $reason,
            ]);

            return;
        }
    }
}
