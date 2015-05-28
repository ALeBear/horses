<?php

namespace horses;

use horses\action\AuthenticatedAction;
use horses\action\DoctrineAwareAction;
use horses\action\LocalizedAction;
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
        $router = $kernel->getRouter();
        try {
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
                $kernel->getAuthenticator()->authenticate($request, $action);
            }

            if ($action instanceof LocalizedAction) {
                /** @var LocalizedAction $action */
                $action->setTranslator($kernel->getTranslator($request));
            }

            /** @var Action $action */
            $responder = $action->execute($request, $router);
        } catch (UnknownRouteException $e) {
            $responder = $kernel->getExceptionResponder()->setException($e, 404);
        } catch (AccessControlException $e) {
            $responder = $kernel->getExceptionResponder()->setException($e, 401);
        } catch (HorsesException $e) {
            $responder = $kernel->getExceptionResponder()->setException($e, 400);
        } catch (Exception $e) {
            $responder = $kernel->getExceptionResponder()->setException($e, 500);
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
