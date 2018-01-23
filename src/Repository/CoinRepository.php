<?php

namespace App\Repository;

use App\Entity\Coin;
use App\Entity\ResourceInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

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

        foreach ($coins as $coin) {
            $qb->orWhere("coin.symbol =:coin_{$i}");
            $qb->setParameter("coin_{$i}", $coin);
            ++$i;
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

    /**
     * {@inheritdoc}
     */
    public function createPaginator(array $criteria = [], array $sorting = [], $itemsPerPage = 10): iterable
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $this->applyCriteria($queryBuilder, $criteria);
        $this->applySorting($queryBuilder, $sorting);

        return $this->getPaginator($queryBuilder, $itemsPerPage);
    }

    /**
     * @param QueryBuilder $queryBuilder
     *
     * @param  int        $maxPerPage
     * @return Pagerfanta
     */
    protected function getPaginator(QueryBuilder $queryBuilder, $maxPerPage = 10): Pagerfanta
    {
        // Use output walkers option in DoctrineORMAdapter should be false as it affects performance greatly (see #3775)
        $pager = new Pagerfanta(new DoctrineORMAdapter($queryBuilder, false, false));
        $pager->setMaxPerPage($maxPerPage);

        return $pager;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param array        $criteria
     */
    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = []): void
    {
        foreach ($criteria as $property => $value) {
            if (!in_array($property, array_merge($this->_class->getAssociationNames(), $this->_class->getFieldNames()), true)) {
                continue;
            }

            $name = $this->getPropertyName($property);

            if (null === $value) {
                $queryBuilder->andWhere($queryBuilder->expr()->isNull($name));
            } elseif (is_array($value)) {
                $queryBuilder->andWhere($queryBuilder->expr()->in($name, $value));
            } elseif ('' !== $value) {
                $parameter = str_replace('.', '_', $property);
                $queryBuilder
                    ->andWhere($queryBuilder->expr()->eq($name, ':'.$parameter))
                    ->setParameter($parameter, $value);
            }
        }
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param array        $sorting
     */
    protected function applySorting(QueryBuilder $queryBuilder, array $sorting = []): void
    {
        foreach ($sorting as $property => $order) {
            if (!in_array($property, array_merge($this->_class->getAssociationNames(), $this->_class->getFieldNames()), true)) {
                continue;
            }

            if (!empty($order)) {
                $queryBuilder->addOrderBy($this->getPropertyName($property), $order);
            }
        }
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getPropertyName(string $name): string
    {
        if (false === strpos($name, '.')) {
            return 'o'.'.'.$name;
        }

        return $name;
    }
}
