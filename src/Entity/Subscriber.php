<?php

namespace App\Entity;

use Gedmo\Timestampable\Traits\Timestampable;

/**
 * Class Subscriber.
 */
class Subscriber
{
    use Timestampable;

    /** @var string */
    private $id;

    /** @var string */
    private $email;

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function getId()
    {
        return $this->id;
    }
}
