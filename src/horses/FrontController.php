<?php

namespace horses;

use horses\action\AuthenticatedAction;
use horses\action\DoctrineAwareAction;
use horses\Exception as HorsesException;
use horses\auth\AccessControlException;
use Symfony\Component\HttpFoundation\Session\Session;
use horses\action\StatefulAction;
use horses\action\Action;
use Exception;

class FrontController
{
    public function run(Request $request, Kernel $kernel)
    {
        try {
            $router = $kernel->getRouter();
            $action = $router->route($request);

            if ($action instanceof StatefulAction) {
                /** @var StatefulAction $action */
                $action->setState($this->getState());
            }

            if ($action instanceof DoctrineAwareAction) {
                /** @var DoctrineAwareAction $action */
                $action->setEntityManager($kernel->getEntityManager());
            }

            if ($action instanceof AuthenticatedAction) {
                /** @var AuthenticatedAction $action */
                $authenticator = $kernel->getAuthenticator();
                $authenticator->authenticate($request, $action);
            }

            /** @var Action $action */
            $responder = $action->execute($request, $router);
        } catch (UnknownRouteException $e) {
            //404
            die('404');
        } catch (AccessControlException $e) {
            //400
            die('401');
        } catch (HorsesException $e) {
            //400
            echo '<pre>';print_r($e);
            die('400');
        } catch (Exception $e) {
            //500
            echo '<pre>';print_r($e);
            die('500');
        }

        $responder->output($router);
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
