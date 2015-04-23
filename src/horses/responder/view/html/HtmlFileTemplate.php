<?php

namespace horses\responder\view\html;

use horses\responder\view\FileTemplate;
use horses\Router;

abstract class HtmlFileTemplate extends FileTemplate
{

    /** @var Router */
    protected $router;

    /**
     * @param Router $router
     */
    public function setRouter(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param string $string
     * @return string
     */
    public function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE);
    }
    /**
     * @param string $string
     * @return string
     */
    public function escapeForAttribute($string)
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE);
    }

    /**
     * @param string $actionClassName
     * @param string[] $queryStringParameters
     * @return string
     */
    public function linkToAction($actionClassName, array $queryStringParameters = [])
    {
        return $this->router->getUrlFromAction($actionClassName, $queryStringParameters);
    }

    /**
     * @param Partial $partial
     * @return string
     */
    public function renderPartial(Partial $partial)
    {
        $partial->addVariables($this->variables);
        $partial->setRouter($this->router);
        return $partial->getRendering();
    }
}
