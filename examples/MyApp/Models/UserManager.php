<?php

namespace MyApp\Models;

use Doctrine\Common\Persistence\ObjectRepository;
use LeagueFw\Manager\DoctrineManager;

/**
 * This is the class which will intermediate between User and the Database via Doctrine.
 * The Access will be guaranteed through the EntityManager (write) and the EntityRepository (read).
 */
class UserManager extends DoctrineManager
{
    /**
     * @return ObjectRepository
     */
    protected function getRepository() : ObjectRepository
    {
        $this->getManager()->getRepository('MyApp:User');
    }
}