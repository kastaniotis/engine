<?php


namespace Iconic\Engine;


use Iconic\Engine\Exception\ActionException;
use Iconic\Engine\Exception\ActorParameterRequiredException;
use Iconic\Engine\Exception\ActorParameterValueRequiredException;
use Iconic\Engine\Exception\ActorRequiredException;
use Iconic\Engine\Exception\ObjectParameterRequiredException;
use Iconic\Engine\Exception\ObjectParameterValueRequiredException;
use Iconic\Engine\Exception\ObjectRequiredException;

class Engine
{
    public function __construct()
    {
        $this->actions = [];
    }

    private array $actions;

    public function getActions()
    {
        return $this->actions;
    }

    public function allow(string $action)
    {
        if(! key_exists($action, $this->actions)){
            $this->actions[$action] = new Action($action);
        }

        return $this->actions[$action];
    }

    public function can(string $actionName, $object = null, $actor = null)
    {
        if(! key_exists($actionName, $this->actions)){
            throw new ActionException($actionName);
        }

        /** @var Action $action */
        $action = $this->actions[$actionName];

        if($action->transition !== null){
            if($object === null) {
                throw new ObjectRequiredException($actionName);
            }

            $subject = $action->transition->subject;
            $expected = $action->transition->initial;

            if(! property_exists($object, $subject))
            {
                throw new ObjectParameterRequiredException($subject);
            }

            $actualSubject = $object->$subject;

            if($actualSubject !== $expected){
                throw new ObjectParameterValueRequiredException($actionName, $subject, $actualSubject, $expected);
            }
        }

        if($action->gate !== null){
            if($actor === null) {
                throw new ActorRequiredException("$actionName");
            }

            $actorParameter = $action->gate->name;
            $expected = $action->gate->value;

            if(! property_exists($actor, $actorParameter))
            {
                throw new ActorParameterRequiredException($actionName, $actorParameter);
            }

            $actorParameterValue = $actor->$actorParameter;

            if($actorParameterValue !== $expected){
                throw new ActorParameterValueRequiredException($actionName, $actorParameter, $actorParameterValue, $expected);
            }
        }

        return true;
    }

    public function apply(string $name, $object = null, $actor = null)
    {
        $this->can($name, $object, $actor);

        if(null === $object){
            throw new ObjectRequiredException($name);
        }

        /** @var Action $action */
        $action = $this->actions[$name];
        $subject = $action->transition->subject;
        $value = $action->transition->final;

        $object->$subject = $value;
    }
}
