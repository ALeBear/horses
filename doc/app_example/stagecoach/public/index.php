<?php

include_once '../../../../vendor/autoload.php';

use horses\Kernel;
use horses\ServerContext;
use horses\FrontController;
use horses\Request;
use horses\responder\ExceptionResponder;

$request = Request::createFromGlobals();
$kernel = new Kernel(dirname(__DIR__), $request->server->get('ENV', Kernel::DEFAULT_ENV), new ServerContext(), new ExceptionResponder());
(new FrontController())->run($request, $kernel);
