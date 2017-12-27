<?php

namespace App\Repository;

use App\Entity\Coin;
use App\Entity\ResourceInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class CoinRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Coin::class);
    }

    public function findBySymbol($coins)
    {
        $i = 0;
        $qb = $this->createQueryBuilder('coin');
        $qb->distinct(true);

        foreach (array_keys($coins) as $coin) {
            $qb->orWhere("coin.symbol =:coin_{$i}");
            $qb->setParameter("coin_{$i}", $coin);
            $i++;
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function remove(ResourceInterface $resource): void
    {
        if (null !== $this->find($resource->getId())) {
            $this->_em->remove($resource);
            $this->_em->flush();
        }
    }
}
