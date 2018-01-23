<?php

namespace App\Controller;

use App\Entity\Subscriber;
use App\Event\UserSubscribedEvent;
use App\Repository\SubscriberRepository;
use App\Request\SubscribeRequest;
use App\Utils\EmonEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class SubscriptionController.
 */
class SubscriptionController
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var SubscriberRepository
     */
    private $subscriberRepository;

    public function __construct(
        ValidatorInterface $validator,
        EventDispatcherInterface $eventDispatcher,
        SubscriberRepository $subscriberRepository
    ) {
        $this->validator = $validator;
        $this->eventDispatcher = $eventDispatcher;
        $this->subscriberRepository = $subscriberRepository;
    }

    public function create(Request $request)
    {
        $subscribeRequest = new SubscribeRequest($request);

        $errors = $this->validator->validate($subscribeRequest);

        if (count($errors) > 0) {
            $messages = [];

            foreach ($errors as $error) {
                $messages[] = $error->getMessage();
            }

            return new JsonResponse(['error' => $messages]);
        }

        $subscriber = new Subscriber();
        $subscriber->setEmail($subscribeRequest->email);
        $this->subscriberRepository->save($subscriber);

        $this->eventDispatcher->dispatch(
            EmonEvents::USER_SUBSCRIBED,
            new UserSubscribedEvent($subscriber)
        );

        return new JsonResponse(['message' => 'You have been successfully subscribed'], Response::HTTP_OK);
    }
}
