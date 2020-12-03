<?php


namespace Iconic\Engine\Exception;


use Throwable;

class ActorRequiredException extends WorkflowException
{
    public function __construct($action)
    {
        parent::__construct("Only specific actors are allowed action $action", $code = 0, $previous = null);
    }
}