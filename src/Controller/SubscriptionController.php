<?php

namespace App\Controller;

use App\Request\SubscribeRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class SubscriptionController
 * @package App\Controller
 */
class SubscriptionController
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function create(Request $request)
    {
        $subscribeRequest = new SubscribeRequest($request);

        $result = $this->validator->validate($subscribeRequest);


    }
}
