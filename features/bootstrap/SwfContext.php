<?php

namespace Continuous\Features\Swf;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Continuous\Swf\Helper\Config;
use Behat\Behat\Tester\Exception\PendingException;

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
     * @Then response should be instance of :arg1
     */
    public function responseShouldBeInstanceOf($arg1)
    {
        if (!($this->getLastResponse() instanceof $arg1)) {
            throw new \Exception('Response should be an instance of ' . $arg1);
        }
    }
}
