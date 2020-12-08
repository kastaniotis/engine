<?php

namespace Iconic\Engine;

class Transition
{
    public $subject;
    public $initial;
    public $final;

    public function __construct(string $subject, $initial, $final)
    {
        $this->subject = $subject;
        $this->initial = $initial;
        $this->final = $final;
    }
}
