
Redirect responses are instances of Illuminate's RedirectResponse class. They contain the proper headers needed to redirect the user to another URL.

There are several ways to generate a RedirectResponse instance. The simplest is to use the global redirect helper:

<?
	Route::get('/dashboard', function () {
    	return redirect('home/dashboard');
	});
?>

To redirect the user to their previous location, such as when a submitted form is invalid, use the global back helper function. 

This feature utilizes the session so make sure the route calling the back function is using the web middleware group:

<?
	Route::post('/user/profile', function () {
    	
    	// Validate the request...

    	return back()->withInput();
	});
?>

Redirecting to Named Routes
===========================

When you call the redirect helper with no parameters, an instance of Illuminate\Routing\Redirector is returned. This allows you to call any method on the Redirector instance.

For example, to generate a RedirectResponse to a named route, use its route method:

<?
	return redirect()->route('login');

	// or if the route has parameters, pass them as an array for the 2nd argument
	return redirect()->route('profile', ['id' => 1]);
?>

If you're redirecting to a route with an "ID" parameter that is being populated from an Eloquent model, you may pass the model itself. The ID will be extracted automatically:

<?
	// For a route with the following URI: /profile/{id}
	return redirect()->route('profile', [$user]);
?>

To customize the value placed in the route parameter, specify the column in the route parameter definition (/profile/{id:slug}) or override the getRouteKey method on your Eloquent model:

<?
	// this function goes in the model file
	public function getRouteKey()
	{
	    return $this->slug;
	}
?>


Redirecting to Controller Actions
=================================

To do this, pass the controller and action name to the action method:

<?
	use App\Http\Controllers\UserController;

	return redirect()->action([UserController::class, 'index']);

	// or this way if controller route requires params
	return redirect()->action(
	    [UserController::class, 'profile'], ['id' => 1]
	);
?>


Redirecting to External Domains
===============================

The away method is for redirects outside your app. It creates a RedirectResponse without any additional URL encoding, validation, or verification:

<?
	return redirect()->away('https://www.google.com');
?>


Redirecting with Flashed Session Data
=====================================

Redirecting to a new URL and flashing data to the session are usually done at the same time. Typically, this is done after successfully performing an action when you flash a success message to the session.

For convenience, you can create a RedirectResponse instance and flash data to the session in a single, fluent method chain:

<?
	Route::post('/user/profile', function () {
    	// ...

    	return redirect('dashboard')->with('status', 'Profile updated!');
	});
?>

After the user is redirected, you can display that flashed message from the session with Blade:

	@if (session('status'))
    	<div class="alert alert-success">
        	{{ session('status') }}
    	</div>
	@endif


Use the withInput method provided by the RedirectResponse instance to flash the current request's input data to the session before redirecting the user to a new location.

This is typically done if the user has encountered a validation error. Once the input has been flashed to the session, you can easily retrieve it during the next request to repopulate the form:

<?
	return back()->withInput();
?>


