<?php

namespace horses\i18n;

use horses\Request;

interface CurrentLocaleStrategy
{
    /**
     * @return string
     */
    public function getLocale(Request $request);
}
