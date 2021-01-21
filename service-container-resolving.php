
The make method is used to resolve a class instance from the container. It accepts the name of the class or interface you wish to resolve:

<?
	use App\Services\Transistor;

	$transistor = $this->app->make(Transistor::class);
?>

If some of your class's dependencies aren't resolvable via the container, you may inject them as an associative array with the makeWith method:

<?
	use App\Services\Transistor;

	$transistor = $this->app->makeWith(Transistor::class, ['id' => 1]); // id is constructor arg
?>

If you're outside of a service provider in part of your code that doesn't have access to the app variable, use the App facade to resolve a class instance from the container:

<?
	use App\Services\Transistor;
	use Illuminate\Support\Facades\App;

	$transistor = App::make(Transistor::class);
?>

If you need the Laravel container instance itself injected into a class being resolved by the container, type-hint it into your class's constructor:

<?
	use Illuminate\Container\Container;


	public function __construct(Container $container)
	{
	    $this->container = $container;
	}
?>


Automatic Injection
===================

You can type-hint a dependency in the constructor of a class that is resolved by the container (including controllers, listeners, middleware, etc) and in the handle method of queued jobs.

In this example, a repository defined by your app is type-hinted in a controller's constructor. The repository will be automatically resolved and injected into the class:

<?
	use App\Repositories\UserRepository;
	
	class UserController extends Controller
	{
	    protected $users;

	    public function __construct(UserRepository $users)
	    {
	        $this->users = $users;
	    }
	}
?>