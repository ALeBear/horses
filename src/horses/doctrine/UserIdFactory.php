<?php

namespace horses\doctrine;

use horses\auth\Credentials;
use horses\auth\UserIdFactory as HorsesUserIdFactory;
use horses\auth\UsernamePasswordCredentials;
use Doctrine\ORM\EntityRepository;

class UserIdFactory implements HorsesUserIdFactory
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
    public function getUserId(Credentials $credentials = null)
    {
        /** @var UsernamePasswordCredentials $credentials */
        $user = $this->userRepository->findOneBy(['username' => $credentials->getUsername()]);
        /** @var User $user */
        if (!$user || !$user->isPasswordValid($credentials->getPassword())) {
            return null;
        }

        return $user->getUserId();
    }
}
