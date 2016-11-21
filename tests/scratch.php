<?php

require dirname(__DIR__) . '/vendor/autoload.php';

class Scratch
{
    /**
     * @var \Continuous\Swf\Service
     */
    protected $service;

    protected $domainName = 'cphp-demo-0.1.0';
    protected $workflowName = 'spaghetti';
    protected $workflowVersion = '0.1.0';

    public function __construct()
    {
        $awsConfig = \Continuous\Swf\Helper\Config::awsConverter(
            \Continuous\Swf\Helper\Config::getSection('aws')
        );

        $this->sdkAws = new \Aws\Sdk($awsConfig);
        $this->swfClient = $this->sdkAws->createClient('Swf');

        $allConfig = \Continuous\Swf\Helper\Config::getAll();

        $this->service = new \Continuous\Swf\Service(new \Continuous\Swf\ServiceConfig(
            $allConfig->swf->domain,
            $allConfig->identity,
            $this->swfClient,
            'Continuous\\Demo\\Swf'
        ));
    }

    public function run()
    {
        $workflow = new \Continuous\Demo\Swf\Spaghetti\SpaghettiWorkflow();

        $workflow
            ->setId(\Ramsey\Uuid\Uuid::uuid4())
            ->setKwisto('Ducasse')
            ->setClient('John')
            ->setOnions(true)
        ;

        $input = json_encode($workflow->extract());

        $domain = $this->swfClient->startWorkflowExecution([
            'domain' => $this->domainName,
            'taskList' => [
                'name' => 'default'
            ],
            'workflowId' => $workflow->getId(),
            'workflowType' => [
                'name' => $workflow::NAME,
                'version' => $workflow::VERSION,
            ],
            'input' => $input,
        ]);
    }
}

$scratch = new Scratch();
$scratch->run();

