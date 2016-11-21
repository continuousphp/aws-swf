<?php

/**
 * Workflow.php
 *
 * @copyright Copyright (c) 2016 Continuous S.A. (https://continuousphp.com)
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 * @file      Service.php
 * @link      https://github.com/continuousphp/aws-swf the canonical source repo
 */
namespace Continuous\Swf\Entity;

/**
 * Workflow
 *
 * @package   Continuous\Swf\Entity
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
abstract class Workflow implements WorkflowInterface
{
    /**
     * @var string uuid4
     */
    protected $id;

    /**
     * @var string parent workflow class name
     */
    protected $parent;

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

    /**
     * @param Workflow $workflow
     * @return $this
     */
    public function setParent(Workflow $workflow)
    {
        $this->parent = get_class($workflow);
        return $this;
    }

    /**
     * @return string
     */
    public function getParent() : string
    {
        return $this->parent;
    }

    abstract public function extract() : array;
    abstract public function hydrate(array $data);
}
