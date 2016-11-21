<?php

namespace Continuous\Demo\Swf\Sauce;

use Continuous\Swf\Entity\Workflow;

/**
 * Class SauceWorkflow
 * @package Continuous\Demo\Swf\Sauce
 */
class SauceWorkflow extends Workflow
{
    const NAME = 'sauce';
    const VERSION = '0.1.0';

    /**
     * @var bool
     */
    protected $onions = false;

    public function getName() : string
    {
        return static::NAME;
    }

    public function getVersion() : string
    {
        return static::VERSION;
    }

    /**
     * @param bool $wantOnions
     * @return $this
     */
    public function setOnions(bool $wantOnions)
    {
        $this->onions = $wantOnions;
        return $this;
    }

    public function extract() :array
    {
        return [
            'parent' => $this->parent,
            'onions' => $this->onions,
        ];
    }

    public function hydrate(array $data)
    {
        $this->parent = $data['parent'];
        $this->onions = $data['onions'];
    }
}
