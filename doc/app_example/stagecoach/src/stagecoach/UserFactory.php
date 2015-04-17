<?php

namespace stagecoach;

use Doctrine\ORM\EntityManager;
use horses\auth\UserFactory as HorsesAuthFactory;
use horses\auth\UserId;

class UserFactory implements HorsesAuthFactory
{
    /** @var EntityManager */
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /** @inheritdoc */
    public function getUserFromId(UserId $userId)
    {
        return $this->entityManager->getRepository('user')->findOneBy(['id' => $userId->getId()]);
    }

}
