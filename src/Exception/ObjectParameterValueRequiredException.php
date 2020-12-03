<?php


namespace Iconic\Engine\Exception;

class ObjectParameterValueRequiredException extends WorkflowException
{
    public function __construct($action, $parameter, $actual, $expected)
    {
        parent::__construct("The transition '$action' cannot be applied for objects with a '$parameter' '$actual'. Expected: '$parameter' is '$expected'", $code = 0, $previous = null);
    }
}