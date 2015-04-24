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
    /** @var  string */
    protected $message;

    /**
     * @param string $url
     * @param string $message
     */
    public function __construct($url, $message = '')
    {
        $this->url = $url;
        $this->message = $message;
    }

    /** @inheritdoc */
    public function output(Router $router)
    {
        header(sprintf('Location: %s?m=%s', $this->url, urlencode($this->message)));
    }
}
