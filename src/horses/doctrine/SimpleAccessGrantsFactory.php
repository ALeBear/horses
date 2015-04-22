<?php

namespace horses\doctrine;

use Doctrine\ORM\EntityRepository;
use horses\auth\AccessGrantsFactory;
use horses\auth\User as AuthUser;

class SimpleAccessGrantsFactory implements AccessGrantsFactory
{
    /** @var EntityRepository */
    protected $codesEntityRepository;

    /**
     * @param EntityRepository $codesEntityRepository
     */
    public function __construct(EntityRepository $codesEntityRepository)
    {
        $this->codesEntityRepository = $codesEntityRepository;
    }

    /** @inheritdoc */
    public function getGrantsForUser(AuthUser $user)
    {
        return new SimpleAccessGrants($this->codesEntityRepository->findBy(['userId' => $user->getUserId()->getId()]));
    }
}
