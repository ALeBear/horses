<?php

namespace horses;

use Exception;
use horses\responder\Responder;

interface ExceptionHandler
{
    /**
     * @param boolean $flag
     * @return $this
     */
    public function setDisplayErrorDetailFlag($flag);

    /**
     * @param Router $router
     * @return $this
     */
    public function setRouter(Router $router);

    /**
     * @param Exception $exception
     * @return Responder
     */
    public function handle404(Exception $exception);

    /**
     * @param Exception $exception
     * @return Responder
     */
    public function handle401(Exception $exception);

    /**
     * @param Exception $exception
     * @param integer $httpCode
     * @return Responder
     */
    public function handleGeneric(Exception $exception, $httpCode);
}
