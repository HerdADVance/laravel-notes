
Almost all service container bindings will be registered with service providers.

Within a service provider, you always have access to the Container via the $this->app property. 

We can register a binding using the bind method, passing the class or interface name that we wish to register along with a closure that returns an instance of the class:

<?
	use App\Services\Transistor;
	use App\Services\PodcastParser;

	$this->app->bind(Transistor::class, function ($app) {
	    return new Transistor($app->make(PodcastParser::class));
	});
?>

Above, we receive the container itself as an argument to the resolver. We then use that container to resolve sub-dependencies of the object we're building.

If you want to interact with the Container outside of a service provider, use the App facade:

<?
	use App\Services\Transistor;
	use Illuminate\Support\Facades\App;

	App::bind(Transistor::class, function ($app) {
	    // ...
	});
?>

There is no need to bind classes to the container if they don't depend on any interfaces. The container doesn't need to be instructed on how to build these objects.


Binding a Singleton
===================

The singleton method binds a class or interface into the container that should only be resolved once. Once the binding is resolved, the same object instance will be returned on subsequent calls to the container:

<?
	use App\Services\Transistor;
	use App\Services\PodcastParser;

	$this->app->singleton(Transistor::class, function ($app) {
	    return new Transistor($app->make(PodcastParser::class));
	});
?>


Binding Instances
===================

The instance method will bind an existing object instance into the container. That instance will always be returned on subsequent calls to the container.

<?
	use App\Services\Transistor;
	use App\Services\PodcastParser;

	$service = new Transistor(new PodcastParser);

	$this->app->instance(Transistor::class, $service);
?>


Binding Interfaces to Implementations
======================================

Assume EventPusher is an interface and RedisEventPusher is an implementation:

We'll register the RedisEventPusher implementation with the service container:

<?
	use App\Contracts\EventPusher;
	use App\Services\RedisEventPusher;

	$this->app->bind(EventPusher::class, RedisEventPusher::class);
?>

This statement tells the container that it should inject the RedisEventPusher when a class needs an implementation of EventPusher.

We would then type-hint the EventPusher interface in the constructor of a class that's resolved by the container:

<?
	public function __construct(EventPusher $pusher)
	{
	    $this->pusher = $pusher;
	}
?>


Contextual Binding
==================

If you have two classes that utilize the same interface, you may wish to inject different implementations into each of those classes.

In this example two controllers may depend on different implementations of Illuminate's Filesystem contract:

<?
	use App\Http\Controllers\PhotoController;
	use App\Http\Controllers\UploadController;
	use App\Http\Controllers\VideoController;
	use Illuminate\Contracts\Filesystem\Filesystem;
	use Illuminate\Support\Facades\Storage;

	$this->app->when(PhotoController::class)
	          ->needs(Filesystem::class)
	          ->give(function () {
	              return Storage::disk('local');
	          });

	$this->app->when([VideoController::class, UploadController::class])
	          ->needs(Filesystem::class)
	          ->give(function () {
	              return Storage::disk('s3');
	          });
?>







