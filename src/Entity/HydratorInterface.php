<?php

namespace Continuous\Swf\Entity;

/**
 * Interface HydratorInterface
 * @package Continuous\Swf\Entity
 */
interface HydratorInterface
{
    public function extract() : array;
    public function hydrate(array $data);
}
