<?php

namespace stagecoach\responder;

use horses\responder\Responder;
use horses\Router;
use stagecoach\responder\login\LoginFormPartial;

class LoginResponder implements Responder
{
    /** @var  string */
    protected $username;
    /** @var  string */
    protected $password;
    /** @var  string */
    protected $message;

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

    /**
     * @param string $text
     * @return $this
     */
    public function setMessage($text)
    {
        $this->message = $text;
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
        $loginPartial = new LoginFormPartial($layout);
        $layout->addPart('content', $loginPartial);
        echo $layout->getRendering();
    }
}
