<?php

namespace horses\responder;

/**
 * @codeCoverageIgnore
 */
class RedirectResponder implements Responder
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
    public function output()
    {
        header(sprintf('Location: %s', $this->url));
    }
}
