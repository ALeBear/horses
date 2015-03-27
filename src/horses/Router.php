<?php

namespace horses;

use horses\action\Action;
use horses\config\Collection as ConfigCollection;
use horses\config\Config;
use Symfony\Component\HttpFoundation\Request;
use horses\config\UnknownConfigException;

class Router
{
    const DEFAULT_ACTION = 'index';
    const CONFIG_SECTION = 'router';
    const CONFIG_KEY_PREFIX = 'prefix';

    /** @var  ServerContext */
    protected $serverContext;
    /** @var  Config */
    protected $config;


    /**
     * @param ServerContext $serverContext
     * @param ConfigCollection $configs
     * @throws UnknownConfigException
     */
    public function __construct(ServerContext $serverContext, ConfigCollection $configs)
    {
        $this->config = $configs->load(self::CONFIG_SECTION)->getSection(self::CONFIG_SECTION);
    }

    /**
     * @param Request $request
     * @return Action
     * @throws UnknownRouteException
     */
    public function route(Request $request)
    {
        list($actionName, $routeParameters) = $this->breakdownRoute($request);
        $actionClass = $this->ucWordize($actionName);
        if (!class_exists($actionClass)) {
            throw new UnknownRouteException(sprintf('Cannod find action: %s', $actionClass));
        }
        $request->query->add($routeParameters);

        return new $actionClass();
    }

    /**
     * @param Request $request
     * @return string[]
     */
    protected function breakdownRoute(Request $request)
    {
        $parts = explode('/', rtrim($request->getPathInfo(), '/'));
        array_shift($parts);
        if ($prefix = $this->config->get(self::CONFIG_KEY_PREFIX)) {
            if (count($parts) && $parts[0] == $prefix) {
                array_shift($parts);
            }
        }

        $actionName = (count($parts) ? array_shift($parts) : self::DEFAULT_ACTION);
        $params = [];
        while (count($parts)) {
            $params[array_shift($parts)] = count($parts) ? array_shift($parts) : null;
        }
        return [$actionName, $params];
    }

  /**
   * Transforms a dashed-string into a UCWorded one: DashedString
   * @param string $dashedString
   * @return string
   */
    public function ucWordize($dashedString)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', strtolower($dashedString))));
    }

    /**
    * Transforms a UpperCasedWords string to a dashed one: upper-case-words
    * @param string $ucWordsString
    * @return string
    */
    public function dash($ucWordsString) {
      return implode('-',array_map('strtolower',
              preg_split('/([A-Z]{1}[^A-Z]*)/', $ucWordsString, -1, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY)));
    }
}
