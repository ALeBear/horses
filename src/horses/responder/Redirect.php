<?php

namespace horses\responder;
use horses\Router;

/**
 * @codeCoverageIgnore
 */
class Redirect implements Responder
{
    /** @var  string */
    protected $url;

    /**
     * @param string $url
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /** @inheritdoc */
    public function output(Router $router)
    {
        header(sprintf('Location: %s', $this->url));
    }
}
