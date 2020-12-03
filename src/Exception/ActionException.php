<?php


namespace Iconic\Engine\Exception;


use Throwable;

class ActionException extends WorkflowException
{
    public function __construct($action)
    {
        $message = "Action '$action' is not allowed";
        parent::__construct($message, $code = 0, $previous = null);
    }
}