<?php

declare(strict_types=1);

namespace App\Entity\Repository;

use Doctrine\ORM\EntityManager;

/**
 * Class AbstractRepository provide pagination mechanism and helper methods for Repository classes.
 */
abstract class AbstractRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @return EntityManager
     */
    public function getManager(): EntityManager
    {
        return $this->getEntityManager();
    }
}
