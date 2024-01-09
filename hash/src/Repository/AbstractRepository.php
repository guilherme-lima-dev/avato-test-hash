<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\Cache\CacheInterface;

abstract class AbstractRepository extends ServiceEntityRepository
{
    private CacheInterface $dataCache;

    public function __construct(
        ManagerRegistry $registry,
        CacheInterface $dataCache
    ) {
        parent::__construct($registry, $this->getEntityClassName());
        $this->dataCache = $dataCache;
    }

    public function save($object): void
    {
        if (!is_object($object) || false === strstr(get_class($object), $this->getClassName())) {
            $exceptionMessage = sprintf('expects %s object, %s given', $this->getClassName(), gettype($object));
            if (is_object($object)) {
                $exceptionMessage = sprintf('expects %s object, %s given', $this->getClassName(), get_class($object));
            }
            throw new \InvalidArgumentException($exceptionMessage);
        }

        $this->getEntityManager()->persist($object);
    }

    public function saveAndFlush($object): void
    {
        $this->save($object);
        $this->getEntityManager()->flush();
    }

    public function delete($object): void
    {
        $this->getEntityManager()->remove($object);
    }

    public function getDataCache(): CacheInterface
    {
        return $this->dataCache;
    }

    abstract public function getEntityClassName(): string;
}
