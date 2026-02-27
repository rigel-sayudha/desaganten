<?php

/*
|--------------------------------------------------------------------------
| Laravel Public Entry Point
|--------------------------------------------------------------------------
|
| This file serves as the entry point for all HTTP requests coming into
| the application. It instantiates the Laravel application, boots it,
| and handles the incoming request through the HTTP kernel.
|
*/

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Bootstrap The Application
|--------------------------------------------------------------------------
|
| Now we'll create the application instance, which sets up all of the
| bindings in the container and bootstrap all of the application
| service providers. This is where we'd put custom bootstrapping logic
| or service provider resolution logic.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Handle The Request
|--------------------------------------------------------------------------
|
| Now we have an instance of the application, we can handle the request
| using the HTTP kernel. This bootstraps the application and gets it
| ready to receive requests from the client.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
