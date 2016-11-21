<?php

namespace Continuous\Swf\Entity;

interface ActivityInterface extends HydratorInterface
{
    public function setId(string $id);

    public function setTaskToken(string $taskToken);

    public function getId() : string;

    public function getTaskToken() : string;

    public function getStatus() : string;

    public function canceled();

    public function completed();

    public function failed();

    public function process();
}
