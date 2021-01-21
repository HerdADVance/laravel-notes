
Laravel's Service Container manages class dependencies and performs dependency injection.

The dependencies are injected into the class via the constructor, or sometimes, setter methods.

Here's an example of a User Repository being injected into the User Controller's constructor:

<?

use App\Repositories\UserRepository; // left out other dependencies for brevity

class UserController extends Controller
{

    protected $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

}

?>


The User Repository being injected above most likely uses Eloquent to retrieve users from the DB. But since it is injected, we can easily swap in another implementation or create a mock one for testing.


Zero-Config Resolution
=====================

If a class has no dependencies or depends on other concrete classes (not interfaces), the container doesn't need to be instructed on how to resolve that class.

In the example below, that route will automatically resolve the Service class and inject it into the route's handler:

<?
	class Service
	{
	    //
	}

	Route::get('/', function (Service $service) {
	    die(get_class($service));
	});
?>

Many classes automatically receive their dependencies through the Service Container (controllers, listeners, middleware, etc)

Dependencies may be type-hinted in the handle method of queued jobs.

In this example, you're type-hinting the Request object onto the route definition in order to easily access the current request.

<?
	use Illuminate\Http\Request;

	Route::get('/', function (Request $request) {
	    // ...
	});
?>


When to Manually Interact with the Container
============================================

Since we're relying on dependency injection and facades, we rarely need to manually interact with the Service Container. Some possible exceptions:

- When writing a class that implements an interface, and that interface needs to be type-hinted on a route or class constructor

- Writing a Laravel package


Service Container Events
========================

The service container fires an event each time it resolves an object. You can listen to this event with the resolving method:

<?
	use App\Services\Transistor;

	$this->app->resolving(Transistor::class, function ($transistor, $app) {
	    // Called when container resolves objects of type "Transistor"...
	});

	$this->app->resolving(function ($object, $app) {
	    // Called when container resolves object of any type...
	});
?>




