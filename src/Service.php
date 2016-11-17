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
use Continuous\Swf\Entity\Activity;
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
            throw new \Exception('You must generate composer autoload_classmap file 
            for be able to reference your namepsace class without camelCase issues');
        }
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
            'domain' => $this->config->domain,
            'taskList' => [
                'name' => $taskList,
            ],
            'identify' => $this->config->identity,
            'maximumPageSize' => 50,
            'reverseOrder' => true,
        ]);

        //TODO try catch result not Aws\Result...

        $workflowType = $result['workflowType'];
        $workflow = $this->getWorkflowEntity($workflowType['name'], $workflowType['version']);

        if (isset($result['input'])) {
            $workflow->hydrate(json_decode($result['input'], true));
        }

        $workflow->process($result);

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
            'domain' => $this->config->domain,
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
     * @param string $name
     * @param $version
     * @return Workflow
     */
    protected function getWorkflowEntity(string $workflowName, string $version) : Workflow
    {
        $className = ClassFinder::findClass($this->config->namespace, $workflowName, 'Workflow');

        return new $className();
    }

    /**
     * @param string $activityName
     * @return Activity
     */
    protected function getActivityEntity(string $activityName) : Activity
    {
        $className = ClassFinder::findClass($this->config->namespace, $activityName, 'Activity');

        return new $className();
    }

    /**
     *
     */
    public function startWorkflow(Workflow $workflowEntity)
    {
        //$workflowEntity->extract();
    }
}
