<?php

namespace stagecoach;

use horses\Request;
use horses\auth\CredentialsStrategy;
use horses\auth\UsernamePasswordCredentials;

class PostCredentialsStrategy implements CredentialsStrategy
{
    /** @inheritdoc */
    public function getCredentials(Request $request)
    {
        return new UsernamePasswordCredentials(
            $request->getPostParam('username'),
            $request->getPostParam('password')
        );
    }
}
