
When you start a new Laravel project, error and exception handling is already configured for you.

The App\Exceptions\Handler class is where all exceptions thrown by your app are logged and then rendered to the user.

The debug option in your config/app.php file determines how much information about an error is actually displayed to the user. 

By default, this option is set to respect the value of the APP_DEBUG environment variable from your .env file.

During local development, APP_DEBUG should be set to true.

In your production environnment, APP_DEBUG should be false. If not, you risk exposing sensitive info to your end users.


The Exception Handler
=======================

All exceptions are handled by the The App\Exceptions\Handler class.

This class contains a register method where you can register custom exception reporting and rendering callbacks.


Reporting Exceptions
====================

Exception reporting is used to log exceptions or send them to an external service like Flare, Bugsnag, or Sentry.

By default, exceptions will be logged based on your logging configuration, but you can log them however you wish.

If you need to report different types of exceptions in different ways, use the reportable method to register a closure that should be executed when an exception of a given type needs to be reported. Laravel will deduce what type of exception the closure reports by examining its type-hint.

<?
	use App\Exceptions\InvalidOrderException;

	public function register()
	{
	    $this->reportable(function (InvalidOrderException $e) {
	        //
	    });
	}
?>

When you register a custom exception reporting callback using the reportable method, Laravel will still log the exception using the default logging configuration for the app.

If you wish to stop the propagation of the exception to your default logging stack, you can use the stop method when defining your callback or return false from the callback:

<?
	$this->reportable(function (InvalidOrderException $e) {
	    //
	})->stop();

	$this->reportable(function (InvalidOrderException $e) {
	    return false;
	});
?>


If available, Laravel automatically adds the current user's ID to every exception's log message as contextual data. You can define your own global contextual data by overriding the context method of your app's Handler class.

This info will be included in every exception's log message written by your app:

<?
	protected function context()
	{
	    return array_merge(parent::context(), [
	        'foo' => 'bar',
	    ]);
	}
?>

If you need to report an exception but continue handling the current request, use the report helper function. It lets you quickly report the exception via the handler without rendering an error page to the user:

<?
	public function isValid($value)
	{
	    try {
	        // Validate the value...
	    } catch (Throwable $e) {
	        report($e);

	        return false;
	    }
	}
?>


Ignoring Exceptions by Type
============================

To ignore and never report some types of exceptions, use the handler's $dontReport property. It's intialized to an empty array. Any classes you add to this property will never be reported although they may still have custom rendering logic:

<?
	use App\Exceptions\InvalidOrderException;

	protected $dontReport = [
	    InvalidOrderException::class,
	];
?>


Rendering Exceptions
====================

By default, the handler will convert exceptions into an HTTP response for you. However, you're free to register a custom rendering closure for exceptions of a given type.

You can do this with the renderable method of your handler.

The closure passed to the renderable method should return a Response instance. Laravel will deduce which type of exception the closure renders by examining its type-hint:

<?
	use App\Exceptions\InvalidOrderException;

	public function register()
	{
	    $this->renderable(function (InvalidOrderException $e, $request) {
	        return response()->view('errors.invalid-order', [], 500);
	    });
}
?>


Reportable and Renderable Exceptions
====================================

Instead of type-checking exceptions in the handler's register method, you can define report and render methods directly on your custom exceptions.

When these methods exist, they'll automatically be called by the framework:

<?
	namespace App\Exceptions;

	use Exception;

	class InvalidOrderException extends Exception
	{
	    public function report()
	    {
	        //
	    }

	    public function render($request)
	    {
	        return response(...);
	    }
	}
?>

If your exception contains custom reporting logic that is only necessary when certain conditions are met, you may need to instruct Laravel to sometimes report the exception using the default exception handling configuration.

To do this, return false from the exception's report method:

<?
	public function report()
	{
	    // Determine if the exception needs custom reporting...

	    return false;
	}
?>


HTTP Exceptions
=======================

Some exceptions describe HTTP error codes from the server. For example, 404, 401, 500 errors. 

In order to generate such a response from anywhere in your app, you can use the abort helper:

	<? abort(404); ?>


Custom HTTP Error Pages
===========================

If you wish to customize the error page for 404 codes, create a resources/views/errors/404.blade.php file.

This file will be served on all 404 errors generated by your app. The views within this directory should match the HTTP status code they correspond to.

The Symfony\Component\HttpKernel\Exception\HttpException instance raised by the abort function will be passed to the view as an $exception variable:

	<h2>{{ $exception->getMessage() }}</h2>

You can publish Laravel's default error page templates using the vendor:publish Artisan command. Once they're published, you can customize them to your liking:

	php artisan vendor:publish --tag=laravel-errors

	
