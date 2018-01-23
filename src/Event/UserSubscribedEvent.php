<?php

namespace App\Event;

use App\Entity\Subscriber;
use Symfony\Component\EventDispatcher\Event;

class UserSubscribedEvent extends Event
{
    /**
     * @var Subscriber
     */
    private $subscriber;

    public function __construct(Subscriber $subscriber)
    {
        $this->subscriber = $subscriber;
    }
}
