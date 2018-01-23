<?php

namespace App\Repository;

use App\Entity\Subscriber;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class SubscriberRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Subscriber::class);
    }

    /**
     * @param $subscriber
     * @throws \Doctrine\ORM\ORMException
     */
    public function save($subscriber)
    {
        $em = $this->getEntityManager();

        $em->persist($subscriber);
        $em->flush();
    }
}
