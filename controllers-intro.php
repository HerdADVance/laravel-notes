
Controllers group related request handling logic into a single class.


Single Action Controllers
=========================

If a controller action, is very complex, you might find it convenient to dedicate an entire controller class to that single action. Use the __invoke method to do this:

<?
	class ProvisionServer extends Controller
	{
	    public function __invoke()
	    {
	        // ...
	    }
	}
?>

When registering routes for a SAC, you don't need to specify the name of the controller method, just the name of the controller:

<?
	use App\Http\Controllers\ProvisionServer;

	Route::post('/server', ProvisionServer::class);
?>


Controller Middleware
=====================

Middleware is usually assigned to the controller's route in your route files. However, you can also specify middleware within your controller's constructor. Then you can assign it to your controller's actions:

<?
class UserController extends Controller
	{
	    public function __construct()
	    {
	        $this->middleware('auth');
	        $this->middleware('log')->only('index');
	        $this->middleware('subscribed')->except('store');
	    }
	}
?>

You can also register middleware with a closure inside the controller. Convenient if you don't want to define an entire middleware class:

<?
	$this->middleware(function ($request, $next) {
	    return $next($request);
	});
?>


