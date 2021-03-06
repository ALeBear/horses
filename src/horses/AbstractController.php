<?php

namespace horses;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ReflectionMethod;

/**
 * Parent class of controllers. Subclasses may implement one of the following
 * methods (will be executed in this order):
 * - prepare()
 * - post() (only called if this is a post request)
 * - execute()
 * - prepareView()
 * These methods are magic: if you declare parameters, the dispatch will try to
 * find variables with the same name in the query and pass them, otherwise
 * will pass the declared default.
 * CAUTION! A parameter with no default value will be mandatory and the
 * dispatch will produce a KernelPanicException if not found in request.
 * 
 * Do not forget the filterMagicParam() method which will be called first before
 * passing the params to the magic methods
 */
abstract class AbstractController
{
    const DEFAULT_METAS = 'title=,charset=utf-8';
    
    /**
     * @var \Symfony\Component\HttpFoundation\Request 
     */
    protected $request;
    
    /**
     * @var \Symfony\Component\HttpFoundation\Response 
     */
    protected $response;
    
    /**
     * @var \Symfony\Component\DependencyInjection\Container 
     */
    protected $dependencyInjectionContainer;
    
    /**
     * Shortcut to the router
     * @var Router 
     */
    protected $router;
    
    /**
     * @var View
     */
    protected $view;
    
    /**
     * The "metas" for the view. This array will be extract()ed to the layout
     * and view. Default can be set in view.yml
     * @var string[]
     */
    protected $metas;
    
    /**
     * The javascript files. A variable named $javascripts will be extracted to
     * the layout and view
     * @var array
     */
    protected $javascripts = array();
    
    /**
     * The css files. A variable named $css will be extracted to
     * the layout and view
     * @var array
     */
    protected $css = array();
    
    
    /**
     * @param \Symfony\Component\DependencyInjection\Container $dependencyInjectionContainer
     * @param View $view
     */
    public function __construct(Container $dependencyInjectionContainer, View $view)
    {
        $this->dependencyInjectionContainer = $dependencyInjectionContainer;
        $this->router = $this->dependencyInjectionContainer->get('router');
        $this->view = $view;
        $this->metas = array_merge(array_reduce(
                explode(',', self::DEFAULT_METAS),
                function (&$result, $elt) { list($key, $val) = explode('=', $elt); $result[$key] = $val; return $result; }, array()),
            $dependencyInjectionContainer->get('config')->get('view.meta', array()));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    public function dispatch(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;

        $params = array();
        foreach ($this->request->query->all() as $name => $value) {
            $params[$name] = $this->filterMagicParam($name, $value);
        }
        $this->callMagicMethod('prepare', $params);
        $this->request->isMethod('post') && $this->callMagicMethod('post', $params);
        $this->callMagicMethod('execute', $params)
            ->callMagicMethod('prepareView', $params)
            ->render();
    }
    
    /**
     * Used to filter query (and path) parameters before passing them to magic
     * methods
     * @param string $name
     * @param mixed $value
     * @return mixed the filtered out value
     */
    protected function filterMagicParam($name, $value)
    {
        return $value;
    }
    
    /**
     * Renders the view
     */
    protected function render()
    {
        if (!$this->hasView()) {
            return;
        }
        
        //Process view file
        ob_start();
        extract($this->prepareMetas()->metas);
        extract(get_object_vars($this->view));
        require $this->view->getFile();
        $content = ob_get_clean();
        
        //Process layout
        if ($this->view->getLayoutFile()) {
            ob_start();
            extract(array($content));
            require $this->view->getLayoutFile();
            $this->response->setContent(ob_get_clean());
        } else {
            $this->response->setContent($content);
        }
        $this->response->send();
    }
    
    /**
     * return horses\AbstractController $this
     */
    protected function prepareMetas()
    {
        //Add action files if they exists
        $jsActionFile = sprintf('/js/%s.%s.js', $this->request->attributes->get('MODULE'), ucfirst($this->request->attributes->get('ACTION')));
        if (file_exists(sprintf('%s%s', $this->request->attributes->get('DIR_HTDOCS'), $jsActionFile))) {
            $this->javascripts[] = $jsActionFile;
        }
        $cssActionFile = sprintf('/css/%s.%s.css',$this->request->attributes->get('MODULE'), ucfirst($this->request->attributes->get('ACTION')));
        if (file_exists(sprintf('%s%s', $this->request->attributes->get('DIR_HTDOCS'), $cssActionFile))) {
            $this->css[] = $cssActionFile;
        }
        $this->metas['javascripts'] = $this->metas['css'] = '';
        foreach ($this->javascripts as $script) {
            $this->metas['javascripts'] .= sprintf('<script type="text/javascript" src="%s"></script>%s', $script, "\n");
        }
        foreach ($this->css as $css) {
            $this->metas['css'] .= sprintf('<link rel="stylesheet" type="text/css" href="%s" />%s', $css, "\n");
        }
        
        return $this;
    }
    
    /**
     * @return string
     */
    protected function getModule()
    {
        return $this->request->attributes->get('MODULE');
    }
    
    /**
     * @return string
     */
    protected function getAction()
    {
        return $this->request->attributes->get('ACTION');
    }
    
    /**
     * Does this controller have a view?
     * @return boolean
     */
    public function hasView()
    {
        return true;
    }

    /**
     * Redirects to another module and action
     * @param string $route The "module/action" combo in one string
     * @param array $query Query string parameters, will be in the url itself
     * @param array $options Options for the router
     */
    protected function redirect($route, array $query = array(), array $options = array())
    {
        $this->router->redirect(new Route($route, $query, $options));
    }

    /**
     * Redirectws to the same module and another action
     * @param string $action
     * @param array $query Query string parameters, will be in the url itself
     * @param array $options Options for the router
     */
    protected function redirectToAction($action, array $query = array(), array $options = array())
    {
        $this->redirect($this->getModule() . '/' . $action, $query, $options);
    }
    
    /**
     * Translates a string. You can pass more parameters, they will be
     * sprintf'ed.
     * If locale plugin not loaded, will return the token.
     * @param string $token
     * @return string
     */
    protected function _($token)
    {
        $locale = $this->dependencyInjectionContainer->get('locale', Container::NULL_ON_INVALID_REFERENCE);
        return $locale ? call_user_func_array(array($locale, '_'), func_get_args()) : $token;
    }
    
    /**
     * Gets the entity manager if doctrine plugin was loaded
     * @return \Doctrine\ORM\EntityManager Null if plugin doctrine not loaded
     */
    protected function getEntityManager()
    {
        return $this->dependencyInjectionContainer->get('entity_manager', Container::NULL_ON_INVALID_REFERENCE);
    }
    
    /**
     * Gets the global config
     * @return \Symfony\Component\Config\QueryableInterface
     */
    protected function getConfig()
    {
        return $this->dependencyInjectionContainer->get('config');
    }
    
    /**
     * @param string $filename
     * @return \horses\AbstractController
     */
    protected function addJs($filename)
    {
        $this->javascripts[] = $filename;
        return $this;
    }
    
    /**
     * @param string $filename
     * @return \horses\AbstractController
     */
    protected function addCss($filename)
    {
        $this->css[] = $filename;
        return $this;
    }
    
    /**
     * Locates a partial, which can then be included. Located at
     * application/controller/horses_partials/$name.php
     * @param string $name
     * @return string
     */
    protected function getPartialFile($name)
    {
        return sprintf('%s/horses_partials/%s.php', $this->request->attributes->get('DIR_CONTROLLERS'), $name);
    }
    
    /**
     * Calls a magic method, one where the user can declare parameters and they
     * will be taken from request (aka query string)
     * @param string $name
     * @param $availableParams $params the parameters available for the methods
     * @return \horses\AbstractController
     * @throws KernelPanicException If a param is mandatory and not found in request
     */
    protected function callMagicMethod($name, $availableParams)
    {
        if (!method_exists($this, $name)) {
            return $this;
        }
        
        $reflectionMethod = new ReflectionMethod($this, $name);
        $params = array();
        foreach ($reflectionMethod->getParameters() as $param) {
            if (isset($availableParams[$param->getName()])) {
                $params[] = $availableParams[$param->getName()];
            } else {
                if (!$param->isDefaultValueAvailable()) {
                    throw new KernelPanicException(sprintf('Parameter missing for action "%s": %s', $this->request->attributes->get('ROUTE'), $param->getName()));
                }
                $params[] = $param->getDefaultValue();
            }
        }
        
        call_user_func_array(array($this, $name), $params);
        
        return $this;
    }
}