 
The routes defined in your route files are automatically loaded by the RouteServiceProvider in the App/Providers directory.

The routes in the web.php file are assigned the web middleware group (session state, CSRF protection, etc).

The routes in the api.php file are stateless and assigned the api middleware group. The /api URI prefix is automatically applied to every route in this file. This can me edited by modifying the RouteServiceProvider class.

<? 
	// get, post, put, patch, delete, options
	Route::get($uri, $callback)
	
	// if you wish to register a route to multiple verbs
	Route::match(['get', 'post'], $uri, $callback)

	// register to all 6 verbs
	Route::get($uri, $callback)  
?>

You can type-hint dependencies required by your route in the callback signature. They will automatically be injected into the callback by the Laravel service container. Request is a common one:

<?
	use Illuminate\Http\Request;

	Route::get('/users', function (Request $request) {
	    // ...
	});
?>

You can easily redirect a route like this:

<?
	// Returns 302 status code by default. "destination" and "status" are reserved and can't be used
	Route::redirect('/here', '/there');

	// Customize the status code like this
	Route::redirect('/here', '/there', 301);

	// This will also return a 301 status code
	Route::permanentRedirect('/here', '/there');
?>

If your route only needs to return a view and doesn't need a controller, do this:

<?
	// 1st arg is URI, 2nd arg is name
	Route::view('/welcome', 'welcome');
	
	// Optional 3rd arg passes array of data. Probably should use controller if too much data here
	Route::view('/welcome', 'welcome', ['name' => 'Taylor']);
?>


Route Parameters
================

<?
	// Basic usage
	Route::get('/user/{id}', function ($id) {
	    return 'User '.$id;
	});

	// Multiple params. $postId and $commentId can be anything you want because they're based on order of appearance in in the 1st arg
	Route::get('/posts/{post}/comments/{comment}', function ($postId, $commentId) {});

	// The ? specifies that the param may not always be present
	Route::get('/user/{name?}', function ($name = null) {
	    return $name;
	});

	// Giving default value to optional param
	Route::get('/user/{name?}', function ($name = 'John') {
	    return $name;
	});

	// One example of the tons of regex options available. Will return 404 if doesn't match pattern
	Route::get('/user/{id}/{name}', function ($id, $name) {
    	//
	})->whereNumber('id')->whereAlpha('name');

?>


Named Routes
==============

<?
	// 1st arg URI, 2nd arg controller, chained route name
	Route::get('/user/profile', [UserProfileController::class, 'show'] )->name('profile');

	// Once you have a named route, you can use the name to generate URL's or redirects
	$url = route('profile');
	return redirect()->route('profile');

	// If the named route defines parameters, you can pass them as the 2nd arg of the route function
	Route::get('/user/{id}/profile', function ($id) {})->name('profile');
	$url = route('profile', ['id' => 1]);

?>


If you want to determine if the current request was routed to a given named route, you can use the named method on a route instance. This is an example from middleware:

<?
	public function handle($request, Closure $next)
	{
	    if ($request->route()->named('profile')) {
	        //
	    }

	    return $next($request);
	}
?>

