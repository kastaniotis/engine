<?php

namespace Iconic\Engine\Exception;

class ActorParameterValueRequiredException extends WorkflowException
{
    public function __construct($action, $parameter, $value, $expected)
    {
        parent::__construct("The action '$action' cannot be applied by '$parameter': '$value'. Expected: '$expected'", $code = 0, $previous = null);
    }
}
