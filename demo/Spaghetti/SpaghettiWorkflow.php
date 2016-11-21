<?php

namespace Continuous\Demo\Swf\Spaghetti;

use Continuous\Swf\Entity\Workflow;

/**
 * Class SpaghettiWorkflow
 * @package Continuous\Demo\Swf\Spaghetti
 */
class SpaghettiWorkflow extends Workflow
{
    const NAME = 'spaghetti';
    const VERSION = '0.1.0';

    /**
     * @var string
     */
    protected $kwisto;

    /**
     * @var string
     */
    protected $client;

    public function getName() : string
    {
        return static::NAME;
    }

    public function getVersion() : string
    {
        return static::VERSION;
    }

    /**
     * @param string $kwisto
     * @return $this
     */
    public function setKwisto(string $kwisto)
    {
        $this->kwisto = $kwisto;
        return $this;
    }

    /**
     * @param string $client
     * @return $this
     */
    public function setClient(string $client)
    {
        $this->client = $client;
        return $this;
    }

    public function extract() :array
    {
        return [
            'parent' => $this->parent,
            'kwisto' => $this->kwisto,
            'client' => $this->client,
        ];
    }

    public function hydrate(array $data)
    {
        $this->parent = $data['parent'];
        $this->kwisto = $data['kwisto'];
        $this->client = $data['client'];
    }
}
