
Illuminate's Request class provides an object-oriented way to interact with the current HTTP request being handled by your application.

It also provides a way to retrieve input, cookies, and files that were submitted with the request.

To obtain an instance of the current HTTP request via dependency injection, type-hint the Request class on your route closure or controller method. The incoming request instance will automatically be injected by Laravel's service container.

<?
	// Request class injected in a controller method
	class UserController extends Controller
	{
	    public function store(Request $request)
	    {
	        $name = $request->input('name');

	        //
	    }
	}


	// Request class injected into route closure
	Route::get('/', function (Request $request) {
	    //
	});
?>

If a controller method is also expecting input from a route parameter, list the parameters after other dependencies:

<?
	// id parameter in route
	Route::put('/user/{id}', [UserController::class, 'update']);

	// then in the controller the id comes after the injected Request
	public function update(Request $request, $id)
    {
        //
    }
?>


Request Path & Methods
=======================

The Request instance extends the Symfony class:
<?
	use Illuminate\Http\Request;
	use Symfony\Component\HttpFoundation\Request;
?>

Various useful methods from the request class:

<?
	// this returns 'foo/bar' from http://example.com/foo/bar
	$uri = $request->path();

	// checks to see if the request path matches a given pattern
	if ($request->is('admin/*')) {
    	//
	}

	// same as above but checking named routes
	if ($request->routeIs('admin.*')) {
    	//	
	}

	// returns full URL
	$url = $request->url();

	// returns full URL with query string
	$urlWithQueryString = $request->fullUrl();

	// returns request verb
	$method = $request->method();

	// checks if request verb matches pattern
	if ($request->isMethod('post')) {
	    //
	}
?>


Request Headers
===============

You can retrieve a request header from the Request instance using the header method. It will return null if a header is not present on the request.

The header method accepts an optional second argument that will be returned if the header is not present on the request:

<?
	$value = $request->header('X-Header-Name');
	
	$value = $request->header('X-Header-Name', 'default');
?>

The hasHeader method checks if the request contains a given header:

	<?
	if ($request->hasHeader('X-Header-Name')) {
	    //
	}
?>

The bearerToken method gets the bearer token from the Authorization header. Will return empty string if not present:

<?
	$token = $request->bearerToken();
?>


Request IP Address
==================

The ip method can be used to retrieve a client's IP address that made a request to your app:

<?
	$ipAddress = $request->ip();
?>


Content Negotiation
===================

Laravel provides several methods for inspecting the incoming request's requested content types via the Accept header.

The getAcceptableContentTypes method will return an array with all of the content types accepted by the request:

<?
	$contentTypes = $request->getAcceptableContentTypes();
?>

The accepts method accepts an array of content types and returns true if ANY of the content types are accepted by the request. Otherwise returns false:

<?
	if ($request->accepts(['text/html', 'application/json'])) {
    	// ...
	}
?>

The prefers method determines which content type out of a given array of content types is most preferred by the request. Returns null if none are accepted:

<?
	$preferred = $request->prefers(['text/html', 'application/json']);
?>

Since many applications only serve HTML or JSON, you can use the expectsJson method to quickly determine if the incoming request expects a JSON response:

<?
	if ($request->expectsJson()) {
    	// ...
	}
?>


PSR-7 Requests
===============

The PSR-7 standard specifies interfaces for HTTP messages, including requests and responses. If you want to obtain an instance of a PSR-7 request instead of a Laravel request, you'll need to install a few libraries:

composer require symfony/psr-http-message-bridge
composer require nyholm/psr7

Once you've installed these libraries, you can obtain a PSR-7 request by type-hinting the request interface on your route closure or controller method:

<?
	use Psr\Http\Message\ServerRequestInterface;

	Route::get('/', function (ServerRequestInterface $request) {
    	//
	});
?>

If you return a PSR-7 response instance from a route or controller, it will automatically be converted back to a Laravel response instance and be displayed the framework.
















