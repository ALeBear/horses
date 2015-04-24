<?php

namespace horses\test\i18n;

use horses\test\AbstractTest;
use horses\i18n\Localizer;

class LocalizerTest extends AbstractTest
{
    /**
     * @expectedException \horses\i18n\UnknownLocaleException
     */
    public function testConstructBadDefaultLocale()
    {
        $availableLocales = ['en'];
        $defaultLocale = 'foo';
        new Localizer($availableLocales, $defaultLocale);
    }

    public function testGetAvailableLocales()
    {
        $availableLocales = ['en'];
        $defaultLocale = 'en';
        $localizer = new Localizer($availableLocales, $defaultLocale);

        $this->assertEquals(['en'], $localizer->getAvailableLocales());

        $availableLocales = ['en', 'fr'];
        $defaultLocale = 'fr';
        $localizer = new Localizer($availableLocales, $defaultLocale);

        $this->assertEquals(['en', 'fr'], $localizer->getAvailableLocales());
    }

    public function testGetDefaultLocale()
    {
        $availableLocales = ['en'];
        $defaultLocale = 'en';
        $localizer = new Localizer($availableLocales, $defaultLocale);

        $this->assertEquals('en', $localizer->getDefaultLocale());

        $availableLocales = ['en', 'fr'];
        $defaultLocale = 'fr';
        $localizer = new Localizer($availableLocales, $defaultLocale);

        $this->assertEquals('fr', $localizer->getDefaultLocale());
    }

    public function testGetCurrentLocale()
    {
        $availableLocales = ['en', 'fr'];
        $defaultLocale = 'en';
        $localizer = new Localizer($availableLocales, $defaultLocale);

        $this->assertEquals('en', $localizer->getCurrentLocale());

        $localizer->setCurrentLocale('fr');

        $this->assertEquals('fr', $localizer->getCurrentLocale());

        $localizer->setCurrentLocale('');

        $this->assertEquals('fr', $localizer->getCurrentLocale());
    }

    /**
     * @expectedException \horses\i18n\UnknownLocaleException
     */
    public function testSetCurrentLocaleUnknown()
    {
        $availableLocales = ['en', 'fr'];
        $defaultLocale = 'en';
        $localizer = new Localizer($availableLocales, $defaultLocale);

        $localizer->setCurrentLocale('foo');
    }
}