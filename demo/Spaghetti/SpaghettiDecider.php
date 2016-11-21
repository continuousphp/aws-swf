<?php

namespace Continuous\Demo\Swf\Spaghetti;

use Continuous\Demo\Swf\BakingPasta\BakingPastaWorkflow;
use Continuous\Demo\Swf\Sauce\SauceWorkflow;
use Continuous\Swf\DataTypes\Decision\CompleteWorkflowExecutionDecision;
use Continuous\Swf\DataTypes\Decision\DecisionTrait;
use Continuous\Swf\DataTypes\Decision\ScheduleActivityTaskDecision;
use Continuous\Swf\DataTypes\Decision\StartChildWorkflowExecutionDecision;
use Continuous\Swf\DataTypes\Event;
use Continuous\Swf\DeciderInterface;
use Ramsey\Uuid\Uuid;

/**
 * Class SpaghettiDecider
 * @package Continuous\Demo\Swf\Spaghetti
 */
class SpaghettiDecider extends SpaghettiWorkflow implements DeciderInterface
{
    use DecisionTrait;

    protected $events;

    public function setEvents(array $events)
    {
        $this->events = $events;
        eval(\Psy\sh());
    }

    public function process()
    {
        //TODO if compile activity finish, start activity eat.

        /*$this->filter(
            $this->events,
            [ Event::ACTIVITY_TASK_COMPLETED ],
            function($event) {
                $name = $event['childWorkflowExecutionCompletedEventAttributes']['workflowType']['name'];
                return in_array($name, ['sauce', 'bakingpasta']);
            }
        );*/

        if (false) {
            $this->finish();
            return;
        }

        //TODO if compile activity finish, start activity eat.

        if (false) {
            $this->eat();
            return;
        }

        $events = $this->filter(
            $this->events,
            [ Event::ACTIVITY_TASK_SCHEDULED ]
        );

        if (0 < count($events)) {
            return;
        }

        $names = $this->filter(
            $this->events,
            [ Event::CHILD_WORKFLOW_EXECUTION_COMPLETED ],
            function ($event) {
                $name = $event['childWorkflowExecutionCompletedEventAttributes']['workflowType']['name'];
                return in_array($name, ['sauce', 'bakingpasta']);
            }
        );

        if (2 === count($names)) {
            $this->compile();
            return;
        }

        $names = $this->filter(
            $this->events,
            [ Event::CHILD_WORKFLOW_EXECUTION_STARTED ]
        );

        if (0 === count($names)) {
            $this->startChildren();
            return;
        }
    }

    public function startChildren()
    {
        $bakingPastaWorkflow = new BakingPastaWorkflow();
        $sauceWorkflow       = new SauceWorkflow();

        $bakingPastaWorkflow
            ->setParent($this)
            ->setId(Uuid::uuid4())
            ->setPasta('spaghetti')
            ->setWeight(250)
        ;

        $sauceWorkflow
            ->setParent($this)
            ->setId(Uuid::uuid4())
            ->setOnions(true)
        ;

        $startChildPasta = new StartChildWorkflowExecutionDecision(
            $bakingPastaWorkflow,
            ['name' => 'default']
        );

        $startChildSauce = new StartChildWorkflowExecutionDecision(
            $sauceWorkflow,
            ['name' => 'default']
        );

        $this
            ->addDecisionTask($startChildPasta)
            ->addDecisionTask($startChildSauce)
        ;
    }

    public function compile()
    {
        $compileActivity = new CompileActivity();
        $compileActivity
            ->setId(Uuid::uuid4())
        ;

        $compileTask = new ScheduleActivityTaskDecision(
            $compileActivity->getId(),
            ['name' => $compileActivity->getName(), 'version' => $compileActivity->getVersion()]
        );

        $this->addDecisionTask($compileTask);
    }

    public function eat()
    {
        $eatActivity = new EatActivity();
        $eatActivity
            ->setId(Uuid::uuid4())
        ;

        $eatTask = new ScheduleActivityTaskDecision(
            $eatActivity->getId(),
            ['name' => $eatActivity->getName(), 'version' => $eatActivity->getVersion()]
        );

        $this->addDecisionTask($eatTask);
    }

    public function finish()
    {
        $this->addDecisionTask(new CompleteWorkflowExecutionDecision());
    }
}
