<?php

namespace App\Event;

use App\Entity\Purchase;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class RegisterSuccessEvent extends Event
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
