<?php

namespace stagecoach\responder;

use horses\Router;
use stagecoach\responder\login\LoginFormPartial;

class LoginResponder extends AbstractResponder
{
    /** @var  string */
    protected $username;
    /** @var  string */
    protected $password;

    /**
     * @param string $username
     * @param string $password
     * @return $this
     */
    public function setCredentials($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        return $this;
    }

    /** @inheritdoc */
    public function output(Router $router)
    {
        $layout = new BasicLayout();
        $layout->setRouter($router);
        $layout->addVariable('menu', '');
        $layout->addVariable('username', $this->username);
        $layout->addVariable('password', $this->password);
        $layout->addVariable('message', $this->message);

        $loginPartial = $this->preparePartial(new LoginFormPartial($layout));
        $layout->addPart('content', $loginPartial);

        echo $layout->getRendering();
    }
}
