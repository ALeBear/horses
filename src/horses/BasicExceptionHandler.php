<?php

namespace horses;

use Exception;
use horses\responder\ExceptionResponder;

class BasicExceptionHandler implements ExceptionHandler
{
    /** @var ExceptionResponder */
    protected $responder;
    /** @var Router */
    protected $router;


    /**
     * @param ExceptionResponder $responder
     */
    public function __construct(ExceptionResponder $responder)
    {
        $this->responder = $responder;
    }

    /** @inheritdoc */
    public function setDisplayErrorDetailFlag($flag)
    {
        $this->responder->setDisplayErrorDetailFlag($flag);
        return $this;
    }

    /** @inheritdoc */
    public function setRouter(Router $router)
    {
        $this->router = $router;
        return $this;
    }

    /** @inheritdoc */
    public function handle404(Exception $exception)
    {
        return $this->responder->setException($exception, 404);
    }

    /** @inheritdoc */
    public function handle401(Exception $exception)
    {
        return $this->responder->setException($exception, 401);
    }

    /** @inheritdoc */
    public function handleGeneric(Exception $exception, $httpCode)
    {
        return $this->responder->setException($exception, $httpCode);
    }
}
