<?php

namespace stagecoach;

use Doctrine\ORM\EntityRepository;
use horses\auth\UserFactory as HorsesAuthFactory;
use horses\auth\UserId;

class UserFactory implements HorsesAuthFactory
{
    /** @var EntityRepository */
    protected $userRepository;

    /**
     * @param EntityRepository $userRepository
     */
    public function __construct(EntityRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /** @inheritdoc */
    public function getUserFromId(UserId $userId)
    {
        return $this->userRepository->findOneBy(['id' => $userId->getId()]);
    }
}
