<?php

namespace stagecoach\action;

use Doctrine\ORM\EntityManager;
use horses\action\Action;
use horses\action\DoctrineAwareAction;
use horses\action\LocalizedAction;
use horses\auth\NoRestrictionAccessPolicy;
use horses\auth\User;
use horses\doctrine\SimpleAccessGrantsFactory;
use horses\doctrine\UserFactory;
use horses\action\StatefulAction;
use horses\action\AuthenticatedAction;
use horses\i18n\Translator;
use horses\responder\AuthenticatedResponder;
use horses\responder\LocalizedResponder;
use stagecoach\responder\AbstractResponder;
use horses\State;
use horses\doctrine\User as DoctrineUser;

abstract class AbstractAction implements Action, StatefulAction, AuthenticatedAction, DoctrineAwareAction, LocalizedAction
{
    /** @var State */
    protected $state;
    /** @var EntityManager */
    protected $entityManager;
    /** @var DoctrineUser */
    protected $user;
    /** @var Translator */
    protected $translator;


    /** @inheritdoc */
    public function setState(State $state)
    {
        $this->state = $state;
    }

    /** @inheritdoc */
    public function getState()
    {
        return $this->state;
    }

    /** @inheritdoc */
    public function getAccessPolicy()
    {
        return new NoRestrictionAccessPolicy();
    }

    /** @inheritdoc */
    public function setAuthentication(User $user = null)
    {
        $this->user = $user;
    }

    /** @inheritdoc */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * @return UserFactory
     */
    public function getUserFactory()
    {
        return new UserFactory(
            $this->entityManager->getRepository('horses\doctrine\User'),
            new SimpleAccessGrantsFactory($this->entityManager->getRepository('horses\doctrine\SimpleAccessCode')));
    }

    /**
     * @param Translator $translator
     * @return $this
     */
    public function setTranslator(Translator $translator)
    {
        $this->translator = $translator;

        return $this;
    }

    /**
     * @param AbstractResponder $responder
     * @return AbstractResponder
     */
    protected function prepareResponder(AbstractResponder $responder)
    {
        if ($responder instanceof LocalizedResponder) {
            $responder->setTranslator($this->translator);
        }

        if ($responder instanceof AuthenticatedResponder) {
            $responder->setUser($this->user);
        }

        return $responder;
    }
}
