<?php

namespace Iconic\Engine\Exception;

class ObjectRequiredException extends WorkflowException
{
    public function __construct($action)
    {
        parent::__construct("Action '$action' allows transitions only for specific objects. No objects are specified.", $code = 0, $previous = null, );
    }
}
