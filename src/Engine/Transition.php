<?php

namespace Iconic\Engine;

class Transition
{
    public $subject;
    public $initial;
    public $final;

    public function __construct(string $subject, string $initial, string $final)
    {
        $this->subject = $subject;
        $this->initial = $initial;
        $this->final = $final;
    }
}
