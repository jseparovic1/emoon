<?php

namespace App\Repository;

use App\Entity\Exchange;
use App\Entity\ResourceInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class ExchangeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exchange::class);
    }

    public function findAllCoins($exchangeId)
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $sql = '
            SELECT DISTINCT symbol 
            FROM coin
            INNER JOIN exchange_coins ec ON ec.coin_id = coin.id
            WHERE ec.exchange_id = :exchangeId;
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['exchangeId' => $exchangeId]);

        $result = $stmt->fetchAll();
        $data = [];

        foreach ($result as $item) {
            $data[] = $item['symbol'];
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function add(ResourceInterface $resource): void
    {
        $this->_em->persist($resource);
        $this->_em->flush();
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
