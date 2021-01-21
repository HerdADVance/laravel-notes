
Middleware provides a convenient mechanism for inspecting and filtering HTTP requests coming into your application.

The most common example is Laravel's included middleware that verifies that the user is authenticated. If not, the middleware redirects the user to the login screen.

All middleware is found in the app/Http/Middleware folder.


Creating Middleware
===================

php artisan make:middleware NameOfMiddleware

The above command places a NameOfMiddleware class within the directory mentioned on line 6.

In this example, we're created a middleware called EnsureTokenIsValid that only allows access to a route if the supplied token input matches a specified value:

<?
	namespace App\Http\Middleware;

	use Closure;

	class EnsureTokenIsValid
	{
	    public function handle($request, Closure $next)
	    {
	        if ($request->input('token') !== 'my-secret-token') {
	            return redirect('home');
	        }

	        return $next($request);
	    }
	}
?>

The $next callback passes the request deeper into the app's next middleware layer.

All middleware is resolved via the service container so you may type-hint any dependencies you need within a middleware's constructor.

Middleware can perform tasks before or after passing the request to the next layer:

<?
	namespace App\Http\Middleware;

	use Closure;

	class BeforeOrAfterMiddleware
	{
	    public function handle($request, Closure $next)
	    {
	        // Perform action before 

	        return $next($request);

	        // Perform action after
	    }
	}
?>


Middleware Parameters
====================

Middleware can also receive additional parameters after the request and next closure:

<?
	namespace App\Http\Middleware;

	use Closure;

	class EnsureUserHasRole
	{
		// In this case, $role is the additional parameter. It will be inserted through a route as in the next example
	    public function handle($request, Closure $next, $role)
	    {
	        if (! $request->user()->hasRole($role)) {
	            // Redirect...
	        }

	        return $next($request);
	    }
	}
?>

The parameters can be specified when defining the route. There can be multiple ones separated by commas.

<?
	Route::put('/post/{id}', function ($id) {})->middleware('role:editor');
?>





