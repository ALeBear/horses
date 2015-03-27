<?php

use horses\Kernel;
use horses\ServerContext;
use horses\FrontController;
use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();
$kernel = new Kernel(dirname(__DIR__), $request->server->get('ENV', Kernel::DEFAULT_ENV), new ServerContext());
$frontController = new FrontController($request, $kernel);