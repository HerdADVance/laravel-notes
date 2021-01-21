
Registering Global Middleware
=============================

Middleware that needs to be run during every HTTP request to your app should be listed in the $middleware property of the app/Http/Kernel.php file:

<?
	// These are the ones that come with a Laravel download
	protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];
?>


Registering Route Middleware
==============================

If you want to assign the middleware to specific routes, first assign the middleware a key in the same kernel file mentioned above.

Do this by adding it to the $routeMiddleware property:

<?
	// The one here are also Laravel's default. You would add your own to it
	protected $routeMiddleware = [
	    'auth' => \App\Http\Middleware\Authenticate::class,
	    'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
	    'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
	    'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
	    'can' => \Illuminate\Auth\Middleware\Authorize::class,
	    'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
	    'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
	    'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
	    'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
	];
?>


Applying Middleware to Routes
=============================

Once it's defined in the kernel, you can use use the middleware method to assign it (or multiple) to a route:

<?
	// Assigning middleware called auth to the profile route
	Route::get('/profile', function () {})->middleware('auth');

	// Or we can assign an array of middleware
	Route::get('/profile', function () {})->middleware(['first', 'second']);

	// You can also call the middleware by its class name (assuming it's imported with the use statement)
	Route::get('/profile', function () {})->middleware(EnsureTokenIsValid::class);
?>


If you're inside a group of routes and want to prevent middleware from being applied to one of them, use the withoutMiddleware method:

<?
	Route::middleware([EnsureTokenIsValid::class])->group(function () {
	    
	    // This route gets the EnsureTokenIsValid middleware
	    Route::get('/', function () {});

	    // This one doesn't
	    Route::get('/profile', function () {})->withoutMiddleware([EnsureTokenIsValid::class]);
	});
?>

The withoutMiddleware method above does not apply to global middleware.


Middleware Groups
=================

You might want to group multiple middleware together under a single key to make them easier to assign to routes. This is done with the middlewareGroups method in the kernel.

There are "web" and "api" groups out of the box that contain commonly used ones. They're automatically provided by the RouteServiceProvider.

<?
	// Once again, these are Laravel's default middleware groups
	protected $middlewareGroups = [
	    'web' => [
	        \App\Http\Middleware\EncryptCookies::class,
	        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
	        \Illuminate\Session\Middleware\StartSession::class,
	        // \Illuminate\Session\Middleware\AuthenticateSession::class,
	        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
	        \App\Http\Middleware\VerifyCsrfToken::class,
	        \Illuminate\Routing\Middleware\SubstituteBindings::class,
	    ],

	    'api' => [
	        'throttle:api',
	        \Illuminate\Routing\Middleware\SubstituteBindings::class,
	    ],
	];
?>

And then they can be assigned to routes just like singular middleware:

<?
	// Adding the web middleware group to the home route
	Route::get('/', function () {})->middleware('web');
?>


Sorting Middleware
==================

Sometimes you might need to control the order in which the middleware is assigned to the route. To do this, use the $middlewarePriority property of the kernel file. 

Unlike the examples above, this property probably doesn't exist by default so you will need to create it:

<?
	protected $middlewarePriority = [
	    \Illuminate\Cookie\Middleware\EncryptCookies::class,
	    \Illuminate\Session\Middleware\StartSession::class,
	    \Illuminate\View\Middleware\ShareErrorsFromSession::class,
	    \Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests::class,
	    \Illuminate\Routing\Middleware\ThrottleRequests::class,
	    \Illuminate\Session\Middleware\AuthenticateSession::class,
	    \Illuminate\Routing\Middleware\SubstituteBindings::class,
	    \Illuminate\Auth\Middleware\Authorize::class,
	];
?>
