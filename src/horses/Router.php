<?php

namespace horses;

use horses\action\Action;
use horses\config\Collection as ConfigCollection;
use horses\config\Config;
use horses\config\UnknownConfigException;

/**
 * This router will build action classes as (ActionName is Wordize()d version of the first URL segment):
 * - $config->get('router.action_template')\ActionName (if the config param is set)
 * - $serverContext->getApplication()\action\ActionName
 */
class Router
{
    const DEFAULT_ACTION = 'index';
    const CONFIG_SECTION = 'router';
    const CONFIG_KEY_PREFIX = 'prefix';
    const CONFIG_KEY_ACTION_NAMESPACE = 'action.namespace';
    const CONFIG_KEY_ACTION_SUBNAMESPACES = 'action.subnamespaces';

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
        $this->serverContext = $serverContext;
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

        $template = $this->config->get(
            self::CONFIG_KEY_ACTION_NAMESPACE,
            sprintf('%s\\action', $this->serverContext->getApplication())
        );
        $actionClass = sprintf('%s\\%s', $template, self::wordize($actionName));

        if (!class_exists($actionClass)) {
            throw new UnknownRouteException(sprintf('Cannot find action: %s', $actionClass));
        }
        $request->query->add($routeParameters);

        return new $actionClass();
    }

    /**
     * @param string $actionClassName
     * @param string[] $queryStringParameters
     * @return string
     */
    public function getUrlFromAction($actionClassName, $queryStringParameters = [])
    {
        $classParts = explode('\\', $actionClassName);
        $actionClassName = array_pop($classParts);
        $putativeSubnamespace = count($classParts) ? array_pop($classParts) : '';
        $url = $this->config->get(self::CONFIG_KEY_PREFIX) ? '/' . $this->config->get(self::CONFIG_KEY_PREFIX) . '/' : '/';
        $subnamespaces = $this->config->get(self::CONFIG_KEY_ACTION_SUBNAMESPACES, []);
        if (is_array($subnamespaces) && count($subnamespaces) && in_array($putativeSubnamespace, $subnamespaces)) {
            $url .= $putativeSubnamespace . '/';
        }
        if (count($queryStringParameters) || $actionClassName != self::DEFAULT_ACTION) {
            $url .= self::dashize($actionClassName);
        }
        foreach ($queryStringParameters as $name => $value) {
            $url .= sprintf('/%s/%s', $name, urlencode($value));
        }

        return $url;
    }

    /**
     * @param Request $request
     * @return string[]
     */
    protected function breakdownRoute(Request $request)
    {
        $actionName = '';
        $parts = explode('/', rtrim($request->getPathInfo(), '/'));
        array_shift($parts);
        if ($prefix = $this->config->get(self::CONFIG_KEY_PREFIX)) {
            if (count($parts) && $parts[0] == $prefix) {
                array_shift($parts);
            }
        }

        $subnamespaces = $this->config->get(self::CONFIG_KEY_ACTION_SUBNAMESPACES, []);
        if (is_array($subnamespaces) && count($subnamespaces) && in_array($parts[0], $subnamespaces)) {
            $actionName = $parts[0] . '\\';
            array_shift($parts);
        }

        $actionName .= (count($parts) ? array_shift($parts) : self::DEFAULT_ACTION);
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
    public static function wordize($dashedString)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', strtolower($dashedString))));
    }

    /**
     * Transforms a UpperCasedWords string to a dashed one: upper-case-words
     * @param string $ucWordsString
     * @return string
     */
    public static  function dashize($ucWordsString) {
      return implode('-',array_map('strtolower',
              preg_split('/([A-Z]{1}[^A-Z]*)/', $ucWordsString, -1, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY)));
    }
}
