<?php

require dirname(__DIR__) . '/vendor/autoload.php';

class Worker
{
    /**
     * @var \Continuous\Swf\Service
     */
    protected $service;

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

    public function work()
    {
        foreach ($this->service->pollActivityGenerator() as $activity) {

            try {
                $activity->process();
            } catch (\Exception $e) {
                $activity->failed();
                $this->service->respondActivityTaskCompleted($activity, $e->getTraceAsString(), $e->getMessage());

                continue;
            }

            $this->service->respondActivityTaskCompleted($activity);
        }
    }
}

$scratch = new Worker();
$scratch->work();
