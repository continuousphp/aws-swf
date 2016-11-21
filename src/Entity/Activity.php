<?php

/**
 * Activity.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      Service.php
 * @link      https://github.com/continuousphp/aws-swf the canonical source repo
 */
namespace Continuous\Swf\Entity;

/**
 * Activity
 *
 * @package   Continuous\Swf\Entity
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
abstract class Activity implements ActivityInterface
{
    const CANCELED = 1;
    const COMPLETED = 2;
    const FAILED = 3;

    /**
     * @var string uuid4
     */
    protected $id;

    /**
     * @var string token
     */
    protected $taskToken;

    /**
     * @var string CANCELED | COMPLETED | FAILED
     */
    protected $status;

    /**
     * @var string uuid4
     */
    public function setId(string $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $taskToken
     * @return $this
     */
    public function setTaskToken(string $taskToken)
    {
        $this->taskToken = $taskToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTaskToken() : string
    {
        return $this->taskToken;
    }

    public function getStatus() : string
    {
        return $this->status;
    }

    public function canceled()
    {
        $this->status = static::CANCELED;
    }

    public function completed()
    {
        $this->status = static::COMPLETED;
    }

    public function failed()
    {
        $this->status = static::FAILED;
    }

    abstract public function extract() : array;
    abstract public function hydrate(array $data);
}
