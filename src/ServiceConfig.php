<?php

namespace Continuous\Swf;

use Aws\Swf\SwfClient;
use Continuous\Swf\Helper\ValueObject;

class ServiceConfig extends ValueObject
{
    /**
     * @var string
     */
    protected $domain;

    /**
     * @var string
     */
    protected $identity;

    /**
     * @var SwfClient
     */
    protected $swfClient;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * ServiceConfig constructor.
     * @param $domain
     * @param $identity
     * @param SwfClient $swfClient
     * @param $namespace
     */
    public function __construct($domain, $identity, SwfClient $swfClient, $namespace)
    {
        $this->domain = $domain;
        $this->identity = $identity;
        $this->swfClient = $swfClient;
        $this->namespace = $namespace;
    }
}
