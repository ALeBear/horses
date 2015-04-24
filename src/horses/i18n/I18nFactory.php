<?php

namespace horses\i18n;

use horses\config\Config;
use horses\config\Collection as ConfigCollection;
use horses\Request;
use horses\ServerContext;

class I18nFactory
{
    const CONFIG_SECTION = 'i18n';
    const DEFAULT_CURRENT_LOCALE_STRATEGY = 'UseDefault';

    /** @var Config */
    protected $config;
    /** @var ServerContext */
    protected $serverContext;


    /**
     * @param ConfigCollection $configCollection
     * @param ServerContext $serverContext
     */
    public function __construct(ConfigCollection $configCollection, ServerContext $serverContext)
    {
        $this->config = $configCollection->load(self::CONFIG_SECTION)->getSection(self::CONFIG_SECTION);
        $this->serverContext = $serverContext;
    }

    /**
     * @param Request $request
     * @return Localizer
     * @throws NoAvailableLocalesException
     */
    public function getLocalizer(Request $request)
    {
        $availableLocales = $this->getAvailableLocales();
        $localizer = new Localizer($availableLocales, $this->getDefaultLocale($availableLocales));
        $localizer->setCurrentLocale($this->getCurrentLocaleStrategy()->getLocale($request));

        return $localizer;
    }

    /**
     * @param Request $request
     * @return Translator
     * @throws NoAvailableLocalesException
     */
    public function getTranslator(Request $request)
    {
        $dictionaryPaths = $this->config->get('dictionary.paths', []);
        if (!is_array($dictionaryPaths)) {
            $dictionaryPaths = [$dictionaryPaths];
        }
        foreach ($dictionaryPaths as $key => $path) {
            $dictionaryPaths[$key] = sprintf('%s/%s', $this->serverContext->getPath(ServerContext::DIR_ROOT), $path);
        }

        return new Translator($this->getLocalizer($request), $dictionaryPaths);
    }

    /**
     * @return string[]
     * @throws NoAvailableLocalesException
     */
    protected function getAvailableLocales()
    {
        $availableLocales = $this->config->get('locale.available');
        if (!$availableLocales) {
            throw new NoAvailableLocalesException('No available locale in configuration');
        }
        if (!is_array($availableLocales)) {
            $availableLocales = [$availableLocales];
        }

        return $availableLocales;
    }

    /**
     * @param string[] $availableLocales
     * @return string
     */
    protected function getDefaultLocale(array $availableLocales)
    {
        $defaultLocale = $this->config->get('locale.default');
        if (!$defaultLocale) {
            $defaultLocale = reset($availableLocales);
        }

        return $defaultLocale;
    }

    /**
     * @return CurrentLocaleStrategy
     * @throws UnknownCurrentLocaleStrategyException
     */
    protected function getCurrentLocaleStrategy()
    {
        $currentLocaleStrategyClass = $this->config->get('locale.strategy_for_current', self::DEFAULT_CURRENT_LOCALE_STRATEGY);
        if (strpos($currentLocaleStrategyClass, '\\') === false) {
            $currentLocaleStrategyClass = sprintf('%s\%sCurrentLocaleStrategy', __NAMESPACE__, $currentLocaleStrategyClass);
        }
        if (!class_exists($currentLocaleStrategyClass)) {
            throw new UnknownCurrentLocaleStrategyException(sprintf('Unknown strategy to look for current locale: %s', $currentLocaleStrategyClass));
        }

        return new $currentLocaleStrategyClass();
    }
}
