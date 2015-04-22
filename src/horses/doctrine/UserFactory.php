<?php

namespace horses\doctrine;

use Doctrine\ORM\EntityRepository;
use horses\auth\AccessGrantsFactory;
use horses\auth\UserFactory as HorsesAuthFactory;
use horses\auth\UserId;

class UserFactory implements HorsesAuthFactory
{
    /** @var EntityRepository */
    protected $userRepository;
    /** @var AccessGrantsFactory */
    protected $accessGrantsFactory;

    /**
     * @param EntityRepository $userRepository
     * @param AccessGrantsFactory $accessGrantsFactory
     */
    public function __construct(EntityRepository $userRepository, AccessGrantsFactory $accessGrantsFactory)
    {
        $this->userRepository = $userRepository;
        $this->accessGrantsFactory = $accessGrantsFactory;
    }

    /** @inheritdoc */
    public function getUserFromId(UserId $userId)
    {
        return $this->userRepository->findOneBy(['id' => $userId->getId()])->setGrantsFactory($this->accessGrantsFactory);
    }
}
