<?php

namespace horses\test\i18n;

use horses\test\AbstractTest;
use horses\i18n\Translator;

class TranslatorTest extends AbstractTest
{
    /** @var Translator */
    protected $translator;
    /** @var \horses\i18n\Localizer */
    protected $localizer;

    
    protected function setUp()
    {
        parent::setUp();
        $this->localizer = $this->getBasicMock('\horses\i18n\Localizer');
        $this->localizer->expects($this->any())
            ->method('getAvailableLocales')
            ->will($this->returnValue(['en_CA', 'fr_CA']));
        $this->translator = new Translator($this->localizer, [$this->getFixturesPath() . '/i18n']);
    }

    public function testGetLocalizer()
    {
        $this->assertEquals($this->localizer, $this->translator->getLocalizer());
    }

    public function testTranslateFR()
    {
        $this->localizer->expects($this->any())
            ->method('getCurrentLocale')
            ->will($this->returnValue('fr_CA'));
        $this->assertEquals('foo-FR', $this->translator->translate('key1'));
        $this->assertEquals('UNKNOWN', $this->translator->translate('UNKNOWN'));
        $this->assertEquals('barHEYgreu-FR', $this->translator->translate('key2', 'HEY'));
    }

    public function testTranslateEN()
    {
        $this->localizer->expects($this->any())
            ->method('getCurrentLocale')
            ->will($this->returnValue('en_CA'));
        $this->assertEquals('foo-EN', $this->translator->translate('key1'));
        $this->assertEquals('UNKNOWN', $this->translator->translate('UNKNOWN'));
        $this->assertEquals('barHEYgreu-EN', $this->translator->translate('key2', 'HEY'));
    }

    /**
     * @expectedException \horses\i18n\UnknownLocaleException
     */
    public function testTranslateUnknownLocale()
    {
        $this->localizer->expects($this->any())
            ->method('getCurrentLocale')
            ->will($this->returnValue('FOO'));
        $this->translator->translate('key1');
    }
}