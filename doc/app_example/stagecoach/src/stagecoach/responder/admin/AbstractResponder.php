<?php

namespace stagecoach\responder\admin;

use horses\i18n\Translator;
use horses\responder\LocalizedResponder;
use horses\responder\Responder;
use horses\Router;

abstract class AbstractResponder implements Responder, LocalizedResponder
{
    /** @var  string */
    protected $username;
    /** @var  string */
    protected $message;
    /** @var Translator */
    protected $translator;

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

    /**
     * @param Translator $translator
     * @return $this
     */
    public function setTranslator(Translator $translator)
    {
        $this->translator = $translator;

        return $this;
    }
}
