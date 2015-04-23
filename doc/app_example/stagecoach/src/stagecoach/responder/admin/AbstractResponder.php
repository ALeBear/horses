<?php

namespace stagecoach\responder\admin;

use horses\responder\Responder;
use horses\Router;

abstract class AbstractResponder implements Responder
{
    /** @var  string */
    protected $username;
    /** @var  string */
    protected $message;

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;
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

    /**
     * @param Router $router
     * @return AdminLayout
     */
    protected function prepareLayout(Router $router)
    {
        $layout = new AdminLayout();
        $layout->setRouter($router);
        $layout->addVariable('username', $this->username);
        $layout->addVariable('message', $this->message);

        return $layout;
    }
}
