<?php

namespace horses;

use horses\action\AuthenticatedAction;
use horses\auth\AuthenticationException;
use horses\auth\Authenticator;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use horses\action\StatefulAction;
use horses\action\Action;
use Exception;

class FrontController
{
    public function run(Request $request, Kernel $kernel)
    {
        try {
            $serverContext = $kernel->getServerContext();
            $configs = $kernel->getConfigCollection();
            $action = $kernel->getRouter()->route($serverContext, $request, $configs);

            if ($action instanceof StatefulAction) {
                /** @var StatefulAction $action */
                $action->setState($this->getState());
            }

            if ($action instanceof AuthenticatedAction) {
                /** @var AuthenticatedAction $action */
                $authenticator = new Authenticator();
                $authenticator->authenticate($action);
            }

            /** @var Action $action */
            $responder = $action->execute($request);
        } catch (UnknownRouteException $e) {
            //404
        } catch (AuthenticationException $e) {
            //400
        } catch (Exception $e) {
            //500
        }

        $responder->output();
    }

    /**
     * @return State
     */
    protected function getState()
    {
        $session = new Session();
        $session->start();

        return new State($session);
    }
}
