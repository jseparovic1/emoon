<?php

namespace App\Request;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class SubscribeRequest
 * @package App\Request
 */
class SubscribeRequest
{
    /** @var string */
    public $email;

    public function __construct(Request $request)
    {
        $this->email = $request->request->get('email');
    }
}
