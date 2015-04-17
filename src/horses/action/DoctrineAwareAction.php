<?php

namespace horses\action;

use Doctrine\ORM\EntityManager;

interface DoctrineAwareAction
{
    /**
     * @param EntityManager $entityManager
     * @return $this
     */
    public function setEntityManager(EntityManager $entityManager);
}
