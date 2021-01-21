
public/index.php File (Entry Point)
====================================

This file loads Composer generated autoloader definition 
<? require __DIR__.'/../vendor/autoload.php';?>

It also retrieves instance of Laravel app from bootstrap/app.php
<? $app = require_once __DIR__.'/../bootstrap/app.php';?>

All requests are sent to either the HTTP kernel or the Console kernel, depending on the type of request.
Most will go to the HTTP kernel:

<? $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class); ?>

The kernel defines an array of bootstrappers that run before the request is executed. They perform internal configuration tasks that you generally don't need to be concerned about.

The kernel also defines a list of HTTP middleware that all requests must pass through before getting to the application.

The kernel's main job is to receive a Request and return a Response. That's what's happening here at the end of the index.php file:

<?
	$response = $kernel->handle(
	    $request = Illuminate\Http\Request::capture()
	);

	$response->send();

	$kernel->terminate($request, $response);
?>


Service Providers
=================

Every major feature offered by Laravel is bootstrapped and configured by a service provider.

All of the service providers are in the config/app.php file's providers array:

<?
'providers' => [

        //Laravel Framework Service Providers...         
        Illuminate\Auth\AuthServiceProvider::class,
        // I cut many more out for brevity
        
        // * Package Service Providers...
        // Empty by default

        // Application Service Providers...
        // These are in the app/Providers directory
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,

    ]
?>

Laravel iterates through all of these providers and instantiates each one. After that, the register method is called on each one. After they're registered, the boot method is called on each one.


Route Service Provider
======================

One of the most important service providers is this one, responsible for handling the request. This (the router) will dispatch the request to a route or controller and run any route-specific middleware.

After being passed through any middleware, the method defined by the route or controller will be executed, and a response will be returned back once again through the middleware.

After receiving the response, the kernel's handle method (line 23 above) returns the response object. Then the public/index.php file calls the send method (line 27 above) which sends the response content to the browser.


App Service Provider
====================

This file comes empty and is the place to add your own bootstrapping and service container bindings.

