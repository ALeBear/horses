<?php

namespace stagecoach\responder\admin;

use horses\responder\Responder;
use horses\Router;

class IndexResponder implements Responder
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

    /** @inheritdoc */
    public function output(Router $router)
    {
        $layout = new AdminLayout();
        $layout->addVariable('content', 'asdf');
        $layout->addVariable('username', $this->username);
        echo $layout->getRendering();
    }
}
