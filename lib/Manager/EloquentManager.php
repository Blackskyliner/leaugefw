<?php
declare(strict_types = 1);

namespace LeagueFw\Manager;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Eloquent\Model;

/**
 * Implements the Eloquent ORM for the ManagerInterface
 */
abstract class EloquentManager implements ManagerInterface
{
    /**
     * @var Manager
     */
    private $eloquent;

    /**
     * @param Manager $eloquent
     */
    public function __construct(Manager $eloquent)
    {
        $this->setEloquentManager($eloquent);
    }

    /**
     * Sets the used Eloquent Manager instance.
     *
     * @param Manager $eloquent
     */
    public function setEloquentManager(Manager $eloquent)
    {
        $this->eloquent = $eloquent;
    }

    /**
     * This function will allow a clean distinct access to more eloquent specific functions on the model.
     * If using this you will bind your Application to the EloquentManager and thus the Eloquent ORM.
     *
     * @return Model
     */
    public abstract function getEloquentModel();

    /**
     * {@inheritdoc}
     * @param Model $entity
     */
    public function save($entity)
    {
        return $entity->save();
    }

    /**
     * {@inheritdoc}
     * @param Model $entity
     */
    public function delete($entity)
    {
        return $entity->delete();
    }

    /**
     * {@inheritdoc}
     * @return Model
     */
    public function find($identifier)
    {
        return forward_static_call_array(
            [get_class($this->getEloquentModel()), 'find'],
            $identifier
        );
    }

    /**
     * {@inheritdoc}
     * @return Model
     */
    public function findBy(array $where = [])
    {
        if ($where === []) {
            return forward_static_call_array(
                [get_class($this->getEloquentModel()), 'all']
            );
        }

        return forward_static_call_array(
            [get_class($this->getEloquentModel()), 'where'],
            $where
        );
    }
}