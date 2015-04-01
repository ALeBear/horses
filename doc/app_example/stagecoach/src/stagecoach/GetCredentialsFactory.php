<?php

namespace stagecoach;

use horses\Request;
use horses\auth\CredentialsFactory;
use horses\auth\UsernamePasswordCredentials;

class GetCredentialsFactory implements CredentialsFactory
{
    /** @inheritdoc */
    public function getCredentials(Request $request)
    {
        return new UsernamePasswordCredentials(
            $request->getGetParam('username'),
            $request->getGetParam('password')
        );
    }
}
