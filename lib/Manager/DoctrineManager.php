<?php
declare(strict_types = 1);

namespace LeagueFw\Manager;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;

/**
 * Implements the Doctrine ORM for the ManagerInterface
 */
abstract class DoctrineManager implements ManagerInterface
{
    /**
     * @var EntityManager
     */
    private $doctrine;

    /**
     * @param EntityManager $doctrine
     */
    public function __construct(EntityManager $doctrine)
    {
        $this->setDoctrineManager($doctrine);
    }

    /**
     * Sets the used Doctrine Manager instance.
     *
     * @param EntityManager $doctrine
     *
     * @return DoctrineManager
     */
    public function setDoctrineManager(EntityManager $doctrine) : self
    {
        $this->doctrine = $doctrine;
        return $this;
    }

    /**
     * @return ObjectRepository
     */
    protected abstract function getRepository() : ObjectRepository;

    /**
     * @return EntityManager
     */
    protected function getManager() : EntityManager
    {
        return $this->doctrine;
    }

    /**
     * {@inheritdoc}
     * @param mixed $entity
     */
    public function save($entity) : bool
    {
        try {
            $this->doctrine->persist($entity);
            $this->doctrine->flush();
            return true;
        } catch (ORMException $e) {
        }

        return false;
    }

    /**
     * {@inheritdoc}
     * @param mixed $entity
     */
    public function delete($entity) : bool
    {
        try {
            $this->doctrine->remove($entity);
            $this->doctrine->flush($entity);
            return true;
        } catch (ORMException $e) {
        }

        return false;
    }

    /**
     * {@inheritdoc}
     * @return mixed
     */
    public function find($identifier)
    {
        return $this->getRepository()->find($identifier);
    }

    /**
     * {@inheritdoc}
     * @return mixed
     */
    public function findBy(array $where = [])
    {
        return $this->getRepository()->findBy($where);
    }
}