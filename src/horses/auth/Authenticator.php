<?php

namespace horses\auth;

use horses\action\AuthenticatingAction;
use horses\action\AuthenticatedAction;
use horses\action\StatefulAction;
use horses\Request;


class Authenticator
{
    /**
     * @param Request $request
     * @param AuthenticatingAction|AuthenticatedAction|StatefulAction $action
     * @return $this
     * @throws AccessControlException When authorization is failed
     */
    public function authenticate(Request $request, AuthenticatedAction $action)
    {
        $user = $this->getUser($request, $action);

        $grants = $user ? $user->getAccessGrants() : new AccessGrantsNone();
        $action->getAccessPolicy()->authorize($grants);

        $action->setAuthentication($user);

        return $this;
    }

    /**
     * @param Request $request
     * @param AuthenticatingAction|AuthenticatedAction|StatefulAction $action
     * @return User|null
     */
    protected function getUser(Request $request, AuthenticatedAction $action)
    {
        if ($action instanceof StatefulAction && $action instanceof AuthenticatingAction) {
            $action->getState()->deleteUserId();
        }

        if ($action instanceof StatefulAction) {
            $userId = $action->getState()->getUserId();
            if ($userId) {
                /** @var AuthenticatedAction $action */
                return $action->getUserFactory()->getUserFromId($userId);
            }
        }

        if ($action instanceof AuthenticatingAction) {
            $userId = $action->getUserIdFactory()->getUserId($action->getCredentialsFactory()->getCredentials($request));
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
