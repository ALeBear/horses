<?php

namespace stagecoach\responder;

use horses\auth\User;
use horses\i18n\Translator;
use horses\responder\AuthenticatedResponder;
use horses\responder\LocalizedResponder;
use horses\responder\Responder;

abstract class AbstractResponder implements Responder, LocalizedResponder, AuthenticatedResponder
{
    /** @var  User */
    protected $user;
    /** @var  string */
    protected $message;
    /** @var Translator */
    protected $translator;

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;
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
     * @param Translator $translator
     * @return $this
     */
    public function setTranslator(Translator $translator)
    {
        $this->translator = $translator;

        return $this;
    }

    /**
     * @param AbstractPartial $partial
     * @return AbstractPartial
     */
    protected function preparePartial(AbstractPartial $partial)
    {
        return $partial->setTranslator($this->translator);
    }
}
