
Service providers are the central place to configure your application. Your application and all of Laravel's core services are bootstrapped via service providers.

The providers array in the config/app.php file contains all of the service provider classes that will be loaded into your application.

Many of these providers are "deferred" which means they won't be loaded on every request but only when the services they provide are needed.


Writing Service Providers
=========================

You can write your own service providers and register them with your Laravel app.

php artisan make:provider NameOfServiceProvider will make a new one for you.

To register your service provider, add it to the array in the config/app.php file (line 4 above)

All service providers extend the Illuminate\Support\ServiceProvider class.

Most service providers contain a register and boot method.

In the register method, only bind things into the service container. Don't attempt to register any listeners, routes, or other pieces of functionality. Otherwise, you might use a service from a provider that isn't loaded yet.

You always have access to the $app property within a service provider method. This provides access to the service container.


Register Method
===============

This example only defines a register method, and uses it to define an implementation of the Riak Connection in the service container.

<?
	namespace App\Providers;

	use App\Services\Riak\Connection;
	use Illuminate\Support\ServiceProvider;

	class RiakServiceProvider extends ServiceProvider
	{

	    public function register()
	    {
	        $this->app->singleton(Connection::class, function ($app) {
	            return new Connection(config('riak'));
	        });
	    }
	}
?>


Boot Method
===============

The boot method is called after all other service providers have been registered. This means you have access to all other services that have been registered by the framework.

Here, we're registering a view composer within the service provider:

<?

	namespace App\Providers;

	use Illuminate\Support\Facades\View;
	use Illuminate\Support\ServiceProvider;

	class ComposerServiceProvider extends ServiceProvider
	{
	    public function boot()
	    {
	        View::composer('view', function () {
	            //
	        });
	    }
	}
?>

You can type-hint dependencies for the boot method. The container will automatically inject any dependencies you need:

<?
	use Illuminate\Contracts\Routing\ResponseFactory;

	public function boot(ResponseFactory $response)
	{
	    $response->macro('serialized', function ($value) {
	        //
	    });
	}
?>


Deferred Providers
==================

If your provider is only registering bindings in the service container, you can choose to defer its registration until one of the bindings is actually needed. 

This improvies the performance of your app since it's not loaded on every request.

To do this, implement the DeferrableProvider interface and define a provides method. This method should return the service container bindings registered by your provider:

<?
	namespace App\Providers;

	use App\Services\Riak\Connection;
	use Illuminate\Contracts\Support\DeferrableProvider;
	use Illuminate\Support\ServiceProvider;

	class RiakServiceProvider extends ServiceProvider implements DeferrableProvider
	{
	    public function register()
	    {
	        $this->app->singleton(Connection::class, function ($app) {
	            return new Connection($app['config']['riak']);
	        });
	    }
	    
	    public function provides()
	    {
	        return [Connection::class];
	    }
	}
?>



