<?php

namespace horses;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ReflectionMethod;

/**
 * Parent class of controllers. Subclasses must implement the execute() method.
 * (and optionnally the prepare() and post() method).
 * These methods are magic: if you declare parameters, the dispatch will try to
 * find variables with the same name in the query and pass them, otherwise
 * will pass the declared default.
 * CAUTION! A parameter with no default value will be mandatory and the
 * dispatch will produce a KernelPanicException if not found in request.
 * Between prepare() and execute(), post() will be called if it's a post request
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
     */
    protected $javascripts = array();
    
    /**
     * The css files. A variable named $css will be extracted to
     * the layout and view
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
            $dependencyInjectionContainer->get('config')->get('view.meta'));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    public function dispatch(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;

        $this->callMagicMethod('prepare');
        $this->request->isMethod('post') && $this->callMagicMethod('post');
        $this->callMagicMethod('execute', true)->render();
    }
    
    /**
     * Renders the view
     */
    public function render()
    {
        //Process view file
        ob_start();
        extract($this->prepareMetas()->metas);
        extract(get_object_vars($this->view));
        require $this->view->getFile();
        $content = ob_get_clean();
        
        //Process layout
        ob_start();
        extract(array($content));
        require $this->view->getLayoutFile();
        $this->response->setContent(ob_get_clean());
        $this->response->send();
    }
    
    /**
     * return horses\AbstractController $this
     */
    protected function prepareMetas()
    {
        //Add action files if they exists
        $jsActionFile = sprintf('/js/%s.%s.js',$this->request->attributes->get('MODULE'), ucfirst($this->request->attributes->get('ACTION')));
        file_exists(sprintf('%s%s', $this->request->attributes->get('DIR_HTDOCS'), $jsActionFile)) && $this->javascripts[] = $jsActionFile;
        $cssActionFile = sprintf('/css/%s.%s.css',$this->request->attributes->get('MODULE'), ucfirst($this->request->attributes->get('ACTION')));
        file_exists(sprintf('%s%s', $this->request->attributes->get('DIR_HTDOCS'), $cssActionFile)) && $this->css[] = $cssActionFile;
        
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
    public function getModule()
    {
        return $this->request->attributes->get('MODULE');
    }
    
    /**
     * @return string
     */
    public function getAction()
    {
        return $this->request->attributes->get('ACTION');
    }

    /**
     * Redirects to another module and action
     * @param string $route The "module/action" combo in one string
     * @param array $query Query string parameters, will be in the url itself
     * @param array $options Options for the router
     */
    public function redirect($route, array $query = array(), array $options = array())
    {
        $this->router->redirect(new Route($route, $query, $options));
    }

    /**
     * Redirectws to the same module and another action
     * @param string $action
     * @param array $query Query string parameters, will be in the url itself
     * @param array $options Options for the router
     */
    public function redirectToAction($action, array $query = array(), array $options = array())
    {
        $this->redirect($this->getModule() . '/' . $action, $query, $options);
    }
    
    /**
     * Translates a string. You can pass more parameters, they will be sprintf'ed
     * @param string $token
     * @return string
     */
    public function _($token)
    {
        return call_user_func_array(array($this->dependencyInjectionContainer->get('locale'), '_'), func_get_args());
    }
    
    /**
     * @param type $filename
     * @return \horses\AbstractController
     */
    public function addJs($filename)
    {
        $this->javascripts[] = $filename;
        return $this;
    }
    
    /**
     * @param type $filename
     * @return \horses\AbstractController
     */
    public function addCss($filename)
    {
        $this->css[] = $filename;
        return $this;
    }
    
    /**
     * Calls a magic method, one where the user can declare parameters and they
     * will be taken from request (aka query string)
     * @param string $name
     * @param boolean $mandatory
     * @return \horses\AbstractController
     * @throws KernelPanicException If a param is mandatory and not found in request
     * @throws KernelPanicException If a mandatory method does not exist
     */
    protected function callMagicMethod($name, $mandatory = false)
    {
        if (!method_exists($this, $name)) {
            if ($mandatory) {
                throw new KernelPanicException(sprintf('Method missing from controller %s->%s()', get_class($this), $name));
            }
            return $this;
        }
        
        $reflectionMethod = new ReflectionMethod($this, $name);
        $params = array();
        foreach ($reflectionMethod->getParameters() as $param) {
            if ($this->request->query->has($param->getName())) {
                $params[] = $this->request->query->get($param->getName());
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