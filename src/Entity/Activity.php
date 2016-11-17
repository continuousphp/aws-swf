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

use Continuous\Swf\ActivityInterface;
use Zend\Hydrator\HydratorInterface;

/**
 * Activity
 *
 * @package   Continuous\Swf\Entity
 * @license   http://opensource.org/licenses/Apache-2.0 Apache License, Version 2.0
 */
abstract class Activity implements ActivityInterface
{
    abstract public function extract() : array;
    abstract public function hydrate(array $data);
}
