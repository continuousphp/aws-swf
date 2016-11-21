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
    /**
     * @var string uuid4
     */
    protected $id;

    /**
     * @var string uuid4
     */
    public function setId(string $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }

    abstract public function extract() : array;
    abstract public function hydrate(array $data);
}
