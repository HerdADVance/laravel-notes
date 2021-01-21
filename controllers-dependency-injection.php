
The service container is used to resolve all Laravel controllers. Because of this, you are able to type-hint any dependencies that the controller may need in the constructor.

Declared dependencies will automatically be resolved and injected into the controller instance.

<?
	namespace App\Http\Controllers;

	use App\Repositories\UserRepository;

	class UserController extends Controller
	{
	    protected $users;

	    // injecting the user repository into the constructor
	    public function __construct(UserRepository $users)
	    {
	        $this->users = $users;
	    }
	}
?>

You can also type-hint dependencies on controller methods. A common use case for method injection is injecting Illuminate's Request instance into a method:

<?
	namespace App\Http\Controllers;

	use Illuminate\Http\Request;

	class UserController extends Controller
	{
	    public function store(Request $request)
	    {
	        $name = $request->name;
	    }
	}
?>

If your controller method is also expecting input from a route parameter, list the arguments after the dependencies:

<?
	// The route
	Route::put('/user/{id}', [UserController::class, 'update']);

	// The user controller's update method
	public function update(Request $request, $id)
    {
        //
    }

?>