<?php

namespace Iconic\Engine;

class Action
{
    public string $name;
    public ?Transition $transition = null;
    public ?Gate $gate = null;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function of(string $subject, string $initial, string $final)
    {
        $this->transition = new Transition($subject, $initial, $final);

        return $this->transition;
    }

    public function if(string $name, string $value)
    {
        $this->gate = new Gate($name, $value);

        return $this->gate;
    }
}
