
Facades provide a static interface to classes that are available from the app's service container.

Laravel ships with many facades that provide access to almost all of its features.

All Laravel facades accessed like this:
<? 
	use Illuminate\Support\Facades\NameOfFacade
?>

Helper Functions
================

Global helper functions provided by Laravel compliment facades to make it easier to interact with common Laravel features.

Some of the common helper functions are: view, response, url, config

This example is using the Response facade by importing it:

<?
	use Illuminate\Support\Facades\Response;

	Route::get('/users', function () {
	    return Response::json([
	        // ...
	    ]);
	});
?>

However, since response is a helper function, it doesn't need to be imported, and can (should) be used like this:

<?
	Route::get('/users', function () {
	    return response()->json([
	        // ...
	    ]);
	});
?>


Scope Creep
===============

Facades don't require injection so therefore it can be easy to use many of them in a single class. This will start to violate SOLID principles so be careful that the class stays small. Split it into smaller classes if necessary.


Testing Benefits
================

It usually wouldn't be possible to mock or stub a static class method, but facades allow this by using proxy method calls to objects resolved from the service container.


Real-Time Facades
=================

You can use these to treat any class in your app as if it were a facade. Here's an imported Publisher contract being injected the standard way:

<?
	use App\Contracts\Publisher;

	public function publish(Publisher $publisher)
    {
        $this->update(['publishing' => now()]);

        $publisher->publish($this);
    }
?>

But by prefixing the Use statment with 'Facades' you don't need to inject the contract:

<?
	use Facades\App\Contracts\Publisher; // Facades prefixed

	public function publish() // no injection
    {
        $this->update(['publishing' => now()]);

        Publisher::publish($this); // double colon instead of arrow
    }
?>

The benefit of a real-time facade is that it maintains testability without being required to explicitly pass an instance.


