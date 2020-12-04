<?php

namespace Iconic\Engine\Test;

class User
{
    private string $role;

    public function __construct(string $role)
    {
        $this->role = $role;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }
}
