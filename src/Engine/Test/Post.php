<?php

namespace Iconic\Engine\Test;

class Post
{
    private string $status;

    public function __construct(string $status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }
}
