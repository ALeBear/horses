<?php

namespace stagecoach;

use Exception;
use horses\BasicExceptionHandler;
use horses\responder\Redirect;
use horses\responder\Responder;
use stagecoach\action\Login;

class ExceptionHandler extends BasicExceptionHandler
{
    /**
     * @param Exception $exception
     * @return Responder
     */
    public function handle404(Exception $exception)
    {
        return $this->responder->setException($exception, 404);
    }

    /**
     * @param Exception $exception
     * @return Responder
     */
    public function handle401(Exception $exception)
    {
        return new Redirect($this->router->getUrlFromAction(Login::class));
    }
}
