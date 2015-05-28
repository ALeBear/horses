<?php

namespace horses\responder;

use Exception;
use RuntimeException;
use horses\Router;

class ExceptionResponder implements Responder
{
    /** @var Exception */
    protected $exception;
    /** @var boolean */
    protected $displayDetailedError = false;
    /** @var integer */
    protected $httpCode = 500;

    public function __construct()
    {
        $this->exception = new RuntimeException('No Exception defined for the Exception responder');
    }

    /**
     * @param boolean $flag
     * @return $this
     */
    public function setDisplayErrorDetailFlag($flag)
    {
        $this->displayDetailedError = (bool) $flag;
        return $this;
    }

    /**
     * @param Exception $exception
     * @param integer $httpCode
     * @return $this
     */
    public function setException(Exception $exception, $httpCode)
    {
        $this->exception = $exception;
        $this->httpCode = $httpCode;
        return $this;
    }

    /**
     * @return string
     */
    protected function getGenericMessage()
    {
        switch ($this->httpCode) {
            case 401:
                return 'Unauthorized';
            case 404:
                return 'Not Found';
            default:
                return 'Internal Server Error';
        }
    }

    /** @inheritdoc */
    public function output(Router $router)
    {
        $genericMessage = $this->getGenericMessage();
        $message = $this->displayDetailedError ? $this->exception->getMessage() : $genericMessage;

        $additionalInfo = $this->displayDetailedError ? sprintf('<div class="additional-info"><ul><li>%s</li></ul></div>', str_replace("\n", "</li><li>", $this->exception->getTraceAsString())) : '';

        echo <<<EOE
<!DOCTYPE html>
<html>
<head>
    <title>{$this->httpCode} $genericMessage</title>
    <style>
        body, html {
            font-family: sans-serif;
            font-size: 15px;
        }
        .content {
            padding: 20px;
            width: 800px;
            margin-left: auto;
            margin-right: auto;
            background-color: lightgrey;
        }
        h1 {
            font-size: 24px;
            font-weight: bold;
        }
        .message {
            font-weight: bold;
            padding-bottom: 40px;
        }
        .additional-info {
            font-weight: bold;
            padding: 4px;
            border: 1px solid grey;
        }
    </style>
</head>
<body>
    <div class="content">
        <h1>{$this->httpCode} Ways to Leave Your Lover</h1>
        <div class="message">$message</div>
        $additionalInfo
    </div>
</body>
</html>
EOE;
    }
}
