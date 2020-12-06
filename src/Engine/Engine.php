<?php

namespace Iconic\Engine;

use Iconic\Engine\Exception\ActionException;
use Iconic\Engine\Exception\ActorParameterRequiredException;
use Iconic\Engine\Exception\ActorParameterValueRequiredException;
use Iconic\Engine\Exception\ActorRequiredException;
use Iconic\Engine\Exception\ObjectParameterValueRequiredException;
use Iconic\Engine\Exception\ObjectRequiredException;
use Iconic\Engine\Exception\WorkflowException;
use Iconic\Uniproperty\Exception\PropertyException;
use Iconic\Uniproperty\Uniproperty;

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

    //TODO: Need test for this
    public function getAvailableActions($object = null, $actor = null)
    {
        $result = [];
        foreach ($this->actions as $name => $action) {
            $result[$name] = $this->can($name, $object, $actor);
        }

        return $result;
    }

    public function allow(string $action)
    {
        if (!key_exists($action, $this->actions)) {
            $this->actions[$action] = new Action($action);
        }

        return $this->actions[$action];
    }

    public function can(string $actionName, $object = null, $actor = null)
    {
        try {
            $this->check($actionName, $object, $actor);

            return true;
        } catch (WorkflowException | PropertyException $exception) {
            return false;
        }
    }

    public function apply(string $name, $object = null, $actor = null)
    {
        $this->check($name, $object, $actor);

        if (null === $object) {
            throw new ObjectRequiredException($name);
        }

        /** @var Action $action */
        $action = $this->actions[$name];
        $subject = $action->transition->subject;
        $value = $action->transition->final;

        UniProperty::set($object, $subject, $value);
    }

    private function check(string $actionName, $object = null, $actor = null)
    {
        if (!key_exists($actionName, $this->actions)) {
            throw new ActionException($actionName);
        }

        /** @var Action $action */
        $action = $this->actions[$actionName];

        if (null !== $action->transition) {
            if (null === $object) {
                throw new ObjectRequiredException($actionName);
            }

            $subject = $action->transition->subject;
            $expected = $action->transition->initial;

            $actualSubject = UniProperty::get($object, $subject);

            if ($actualSubject !== $expected) {
                throw new ObjectParameterValueRequiredException($actionName, $subject, $actualSubject, $expected);
            }
        }

        if (null !== $action->gate) {
            if (null === $actor) {
                throw new ActorRequiredException("$actionName");
            }

            $actorParameter = $action->gate->name;
            $expected = $action->gate->value;

            if (!UniProperty::check($actor, $actorParameter)) {
                throw new ActorParameterRequiredException($actionName, $actorParameter);
            }

            $actorParameterValue = UniProperty::get($actor, $actorParameter);

            if ($actorParameterValue !== $expected) {
                throw new ActorParameterValueRequiredException($actionName, $actorParameter, $actorParameterValue, $expected);
            }
        }
    }
}
