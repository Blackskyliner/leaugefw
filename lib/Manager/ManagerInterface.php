<?php
declare(strict_types = 1);

namespace LeagueFw\Manager;

/**
 * Basic Database Manager abstraction, which will allow easy CRUD access to the database through an
 * Library agnostic interface.
 *
 * It will be very limited because of the nature of ORM philosophies. Its the most basic access.
 * The implementations for different specific ORMs may add functions to themselves for specific,
 * ORM specific access/functions. Which will then bind the application code to that specific ORM though.
 */
interface ManagerInterface
{
    /**
     * Saves the given entity to the database and may auto-generate and set increments.
     *
     * @param object $entity
     *
     * @return bool
     */
    public function save($entity);

    /**
     * Deletes the given entity from the database.
     *
     * @param object $entity
     *
     * @return bool
     */
    public function delete($entity);

    /**
     * Search for an single entity and return it, if found by the given (primary) identifier.
     *
     * @param mixed $identifier
     *
     * @return object|null
     */
    public function find($identifier);

    /**
     * Search for all entities matching the given elements in the $where array statements.
     *
     * If $where is empty all entities should be returned.
     * For complex selection-queries explicit functions within the implemented Manager should be used!
     * The Statements in the where array are evaluated as AND composition.
     *
     * @param array $where
     *
     * @return object|null
     */
    public function findBy(array $where = []);
}