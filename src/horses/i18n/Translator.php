<?php

namespace horses\i18n;


class Translator
{
    const DICTIONARY_FILE_FORMAT = '%s.LOCALE.ini';

    /** @var Localizer */
    protected $localizer;
    /** @var string[] */
    protected $dictionaryPaths;
    /** @var string[] */
    protected $dictionaries;


    /**
     * @param Localizer $localizer
     * @param string[] $dictionaryPaths
     */
    public function __construct(Localizer $localizer, array $dictionaryPaths)
    {
        $this->localizer = $localizer;
        $this->dictionaryPaths = $dictionaryPaths;
    }

    /**
     * @return Localizer
     */
    public function getLocalizer()
    {
        return $this->localizer;
    }

    /**
     * Translates a string. You can pass more parameters, they will be sprintf'ed into the resulting translation
     * @param string $token
     * @return string
     */
    public function translate($token)
    {
        $params = func_get_args();
        array_shift($params);
        foreach ($this->getDictionaries($this->localizer->getCurrentLocale()) as $dictionary) {
            if (array_key_exists($token, $dictionary)) {
                return vsprintf($dictionary[$token], $params);
            }
        }

        return $token;
    }

    /**
     * @param string $locale
     * @return string[][]
     * @throws UnknownLocaleException
     */
    protected function getDictionaries($locale)
    {
        if (is_null($this->dictionaries)) {
            $this->dictionaries = $this->loadDictionaries($this->dictionaryPaths, $this->localizer->getAvailableLocales());
        }

        if (!array_key_exists($locale, $this->dictionaries)) {
            throw new UnknownLocaleException(sprintf('Cannot translate for unknown locale %s', $locale));
        }

        return $this->dictionaries[$locale];
    }

    /**
     * @param string[] $paths
     * @param string[] $availableLocales
     * @return string[][]
     */
    protected function loadDictionaries(array $paths, array $availableLocales)
    {
        $dictionaries = [];
        foreach ($availableLocales as $availableLocale) {
            $dictionaries[$availableLocale] = [];
        }
        $dictionaryFileFormat = str_replace(
            ['%s', 'LOCALE'],
            ['.*', sprintf('(?<locale>%s)', implode('|', $availableLocales))],
            self::DICTIONARY_FILE_FORMAT
        );

        foreach ($paths as $path) {
            if (!is_dir($path)) {
                continue;
            }

            foreach (scandir($path) as $file) {
                $matches = [];
                if (!preg_match(sprintf('/%s/', $dictionaryFileFormat), $file, $matches)
                    || !array_key_exists($matches['locale'], $dictionaries)) {
                    continue;
                }

                $dictionaries[$matches['locale']][] =
                    parse_ini_file(sprintf('%s/%s', rtrim($path, '/'), $file));
            }
        }

        return $dictionaries;
    }
}