<?php

namespace Continuous\Demo\Swf\BakingPasta;

use Continuous\Swf\Entity\Workflow;

/**
 * Class BakingPastaWorkflow
 * @package Continuous\Demo\Swf\BakingPasta
 */
class BakingPastaWorkflow extends Workflow
{
    const NAME = 'bakingpasta';
    const VERSION = '0.1.0';

    protected $pasta;
    protected $weight;

    public function getName() : string
    {
        return static::NAME;
    }

    public function getVersion() : string
    {
        return static::VERSION;
    }

    /**
     * Type of pasta
     *
     * @param $pasta
     * @return $this
     * @throws \Exception
     */
    public function setPasta($pasta)
    {
        if ('spaghetti' !== $pasta) {
            throw new \Exception('Only spaghetti pasta is supported');
        }

        $this->pasta = $pasta;
        return $this;
    }

    /**
     * Gram of pasta
     *
     * @param int $weight
     * @return $this
     */
    public function setWeight(int $weight)
    {
        $this->weight = $weight;
        return $this;
    }

    public function extract() :array
    {
        return [
            'parent' => $this->parent,
            'pasta' => $this->pasta,
            'weight' => $this->weight,
        ];
    }

    public function hydrate(array $data)
    {
        $this->parent = $data['parent'];
        $this->pasta = $data['pasta'];
        $this->weight = $data['weight'];
    }
}
