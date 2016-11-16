<?php

namespace Continuous\Features\Swf;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Continuous\Swf\Helper\Config;
use Behat\Behat\Tester\Exception\PendingException;
use Ramsey\Uuid\Uuid;

/**
 * Defines application features from the specific context.
 */
class SwfContext implements Context
{
    /**
     * @var \Aws\Sdk
     */
    protected $sdkAws;

    /**
     * @var \Aws\Swf\SwfClient
     */
    protected $swfClient;

    /**
     * Last SWF API response
     * @var mixed
     */
    protected $lastResponse;

    protected $domainName;
    protected $domainStatus;
    protected $workflowName;
    protected $workflowVersion;


    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $config = Config::awsConverter(
            Config::getSection('aws')
        );

        $this->sdkAws = new \Aws\Sdk($config);
        $this->swfClient = $this->sdkAws->createClient('Swf');
    }

    /**
     * Returns the response of the last aws swf request
     */
    public function getLastResponse()
    {
        if (null === $this->lastResponse) {
            throw new \LogicException('No request sent yet.');
        }

        return $this->lastResponse;
    }

    public function setLastResponse($lastResponse)
    {
        $this->lastResponse = $lastResponse;
    }

    /**
     * @Given domain name as :arg1
     */
    public function domainNameAs($arg1)
    {
        $this->domainName = $arg1;
    }

    /**
     * @Given are REGISTERED
     */
    public function areRegistered()
    {
        $this->domainStatus = 'REGISTERED';
    }

    /**
     * @Given workflow name as :arg1
     */
    public function workflowNameAs($arg1)
    {
        $this->workflowName = $arg1;
    }

    /**
     * @Given workflow version as :arg1
     */
    public function workflowVersionAs($arg1)
    {
        $this->workflowVersion = $arg1;
    }

    /**
     * @When I send describeDomain request to SWF
     */
    public function iSendDescribedomainRequestToSwf()
    {
        $domain = $this->swfClient->describeDomain([
            'name' => $this->domainName,
        ]);

        $this->setLastResponse($domain);
    }

    /**
     * @When I send startWorkflowExecution request to SWF
     */
    public function iSendStartworkflowexecutionRequestToSwf()
    {
        $domain = $this->swfClient->startWorkflowExecution([
            'domain' => $this->domainName,
            'taskList' => [
                'name' => 'default'
            ],
            'workflowId' => Uuid::uuid4(),
            'workflowType' => [
                'name' => $this->workflowName,
                'version' => $this->workflowVersion,
            ]
        ]);

        $this->setLastResponse($domain);
    }

    /**
     * @Then response should be instance of :arg1
     */
    public function responseShouldBeInstanceOf($arg1)
    {
        if (!($this->getLastResponse() instanceof $arg1)) {
            throw new \Exception('Response should be an instance of ' . $arg1);
        }
    }
}
