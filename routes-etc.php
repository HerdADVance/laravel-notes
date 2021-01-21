
Fallback Routes
==============

You can use the Route::fallback method to define a route that will be executed when no other route matches the incoming request.

These requests would otherwise go to a 404 page.

You can add middleware as needed in the route. And it should alwys be the last route registered:

<?
	Route::fallback(function () {
    	//
	});
?>


Accessing the Current Route
===========================

You can use the current, currentRouteName, or currentRouteAction methods from the Route facade to quickly access info about the route handling the incoming request:

<?
	use Illuminate\Support\Facades\Route;

	$route = Route::current(); // Illuminate\Routing\Route
	$name = Route::currentRouteName(); // string
	$action = Route::currentRouteAction(); // string
?>



Route Caching
=================

In production, you should take advantage of Laravel's route cache. This will dramatically decrease the amount of time it takes to register all your app's routes.

To generate a route cache, use the Artisan command:

php artisan route:cache

After running this command, your cached routes file will be loaded on every request. If you add new routes, you need to generate a fresh route cache. Only run the command during deployment.

To clear the cache, run php artisan route:clear