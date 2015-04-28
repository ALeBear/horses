<?php

namespace stagecoach\responder;

use horses\i18n\Translator;
use horses\responder\view\html\Partial;

abstract class AbstractPartial extends Partial
{
    /** @var Translator */
    protected $translator;

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
