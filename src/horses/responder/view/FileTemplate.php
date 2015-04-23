<?php

namespace horses\responder\view;

abstract class FileTemplate implements Composite
{
    /** @var mixed[] */
    protected $variables = [];
    /** @var View[] */
    protected $parts = [];


    /** @inheritdoc */
    public function addVariable($name, $value)
    {
        $this->variables[$name] = $value;
        return $this;
    }

    /** @inheritdoc */
    public function addVariables(array $variables)
    {
        $this->variables = array_merge($this->variables, $variables);
        return $this;
    }

    /** @inheritdoc */
    public function addPart($name, View $part)
    {
        $this->parts[$name] = $part;
        return $this;
    }

    /**
     * @inheritdoc
     * @throws TemplateNotFoundException
     */
    public function getRendering()
    {
        $templateFile = $this->getTemplatePath();
        if (!file_exists($templateFile)) {
            throw new TemplateNotFoundException(sprintf('Cannot find template for TemplateView: %s', $templateFile));
        }

        $renderedParts = [];
        foreach (array_keys($this->parts) as $partName) {
            $renderedParts[$partName] = $this->parts[$partName]->addVariables($this->variables)->getRendering();
        }

        extract($this->variables);
        extract($renderedParts);
        ob_start();
        include $this->getTemplatePath();
        return ob_get_clean();
    }

    /**
     * Ought to be used for pre-rendering calculations
     * @return $this
     */
    protected function preRender()
    {
        return $this;
    }

    /**
     * @return string
     */
    abstract protected function getTemplatePath();
}
