<?php

namespace stagecoach;

use horses\Request;
use horses\auth\CredentialsFactory;
use horses\auth\UsernamePasswordCredentials;

class PostCredentialsFactory implements CredentialsFactory
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
