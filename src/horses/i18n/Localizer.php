<?php

namespace horses\i18n;


class Localizer
{
    /** @var string[] */
    protected $availableLocales;
    /** @var string */
    protected $defaultLocale;
    /** @var string */
    protected $currentLocale;


    /**
     * @param string[] $availableLocales
     * @param string $defaultLocale
     * @throws UnknownLocaleException
     */
    public function __construct(array $availableLocales, $defaultLocale)
    {
        if (!in_array($defaultLocale, $availableLocales)) {
            throw new UnknownLocaleException(sprintf('Cannot have default locale %s not in the available locale list: %s', $defaultLocale, implode(', ', $availableLocales)));
        }

        $this->availableLocales = $availableLocales;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * Sets the current locale. Ignores setting of null.
     * @param string $locale
     * @return $this
     * @throws UnknownLocaleException
     */
    public function setCurrentLocale($locale)
    {
        if (!$locale) {
            return $this;
        }

        if (!in_array($locale, $this->availableLocales)) {
            throw new UnknownLocaleException(sprintf('Cannot set current locale %s, not in the available locale list: %s', $locale, implode(', ', $this->availableLocales)));
        }

        $this->currentLocale = $locale;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getAvailableLocales()
    {
        return $this->availableLocales;
    }

    /**
     * @return string
     */
    public function getDefaultLocale()
    {
        return $this->defaultLocale;
    }

    /**
     * @return string
     */
    public function getCurrentLocale()
    {
        return $this->currentLocale ?: $this->defaultLocale;
    }
}