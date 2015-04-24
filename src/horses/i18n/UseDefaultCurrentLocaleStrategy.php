<?php

namespace horses\i18n;

use horses\Request;

/**
 * @codeCoverageIgnore
 */
class UseDefaultCurrentLocaleStrategy implements CurrentLocaleStrategy
{
    /** @inheritdoc */
    public function getLocale(Request $request)
    {
        //This will have the Localizer use the default
        return null;
    }
}
