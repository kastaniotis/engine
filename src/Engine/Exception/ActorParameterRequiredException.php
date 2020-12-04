<?php

namespace Iconic\Engine\Exception;

class ActorParameterRequiredException extends WorkflowException
{
    public function __construct($action, $parameter)
    {
        parent::__construct("The action '$action' can only be applied by actors with a defined '$parameter'.", $code = 0, $previous = null);
    }
}
