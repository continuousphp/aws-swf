<?php

namespace Continuous\Swf\Entity;

/**
 * Interface Workflow
 */
interface WorkflowInterface extends HydratorInterface
{
    /**
     * Get the workflow name as registered in SWF
     * @return string.
     */
    public function getName() : string;

    /**
     * Get the version of workflow as registered in SWF
     * @return string
     */
    public function getVersion() : string;

    public function setId(string $id);
    public function getId() : string;

    public function setParent(Workflow $workflow);
    public function getParent() : string;
}
