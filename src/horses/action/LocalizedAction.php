<?php

namespace horses\action;

use horses\i18n\Translator;

interface LocalizedAction
{
    /**
     * @param Translator $translator
     * @return $this
     */
    public function setTranslator(Translator $translator);
}
