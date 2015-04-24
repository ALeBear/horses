<?php

namespace horses\responder;

use horses\i18n\Translator;

interface LocalizedResponder
{
    /**
     * @param Translator $translator
     * @return $this
     */
    public function setTranslator(Translator $translator);
}
