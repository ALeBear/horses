<?php

namespace stagecoach;


use horses\auth\Credentials;
use horses\auth\UserId;
use horses\auth\UserIdFactory;
use horses\auth\UsernamePasswordCredentials;

class DummyUserIdFactory implements UserIdFactory
{
    /** @inheritdoc */
    public function getUserId(Credentials $credentials = null)
    {
        /** @var UsernamePasswordCredentials $credentials */
        if ($credentials->getUsername() == 'username' && $credentials->getPassword() == 'password') {
            return new UserId(3);
        }

        return null;
    }
}
