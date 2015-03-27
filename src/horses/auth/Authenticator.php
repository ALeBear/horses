<?php

namespace horses\auth;

use horses\action\AuthenticatingAction;
use horses\action\AuthenticatedAction;
use horses\action\StatefulAction;


class Authenticator
{
    /**
     * @param AuthenticatingAction|AuthenticatedAction|StatefulAction $action
     * @return $this
     * @throws AuthenticationException When authentication is missed
     */
    public function authenticate(AuthenticatedAction $action)
    {
        $user = $this->getUser($action);
        $authorizationNeeded = $action->getAuthorizationNeeded();
        if (!is_null($authorizationNeeded)) {
            if (!$user || !$user->hasAuthorization($authorizationNeeded)) {
                throw new AuthenticationException(
                    sprintf('Missed authentication for action %s requesting authorization %s',
                        get_class($action),
                        $authorizationNeeded->__toString()
                    )
                );
            }
        }

        $action->setAuthentication($user);

        return $this;
    }

    /**
     * @param AuthenticatingAction|AuthenticatedAction|StatefulAction $action
     * @return User|null
     */
    protected function getUser(AuthenticatedAction $action)
    {
        if ($action instanceof StatefulAction) {
            $userId = $action->getState()->getUserId();
            if ($userId) {
                /** @var AuthenticatedAction $action */
                return $action->getUserFactory()->getUserFromId($userId);
            }
        }

        if ($action instanceof AuthenticatingAction) {
            $userId = $action->getAuthenticator()->getUserId($action->getCredentialsFactory()->getCredentials());
            if ($userId) {
                if ($action instanceof StatefulAction) {
                    $action->getState()->saveUserId($userId);
                }
                /** @var AuthenticatedAction $action */
                return $action->getUserFactory()->getUserFromId($userId);
            }
        }

        return null;
    }
}
