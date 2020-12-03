<?php


namespace Iconic\Engine\Exception;


use Throwable;

class ObjectParameterRequiredException extends WorkflowException
{
    public function __construct($parameter)
    {
        parent::__construct("The transition can only be applied to objects with a defined '$parameter'.", $code = 0, $previous = null);
    }
}