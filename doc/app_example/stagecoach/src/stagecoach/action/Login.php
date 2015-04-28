<?php

namespace stagecoach\action;

use horses\action\AuthenticatingAction;
use horses\responder\Redirect;
use horses\Request;
use horses\doctrine\UserIdFactory;
use stagecoach\action\admin\Index;
use stagecoach\PostCredentialsStrategy;
use stagecoach\responder\LoginResponder;
use horses\Router;

class Login extends AbstractAction implements AuthenticatingAction
{
    /** @inheritdoc */
    public function getCredentialsFactory()
    {
        return new PostCredentialsStrategy();
    }

    /** @inheritdoc */
    public function getUserIdFactory()
    {
        return new UserIdFactory($this->entityManager->getRepository('horses\doctrine\User'));
    }

    /** @inheritdoc */
    public function execute(Request $request, Router $router)
    {
        if ($this->getState()->getUserId()) {
            return new Redirect($router->getUrlFromAction(Index::class));
        }

        /** @var LoginResponder $responder */
        $responder = $this->prepareResponder(new LoginResponder());

        if ($request->isMethod(Request::METHOD_POST)) {
            $responder->setMessage($this->translator->translate('wrong_credentials'));
        }

        $responder->setCredentials($request->getPostParam('username'), $request->getPostParam('password'));

        return $responder;
    }
}
