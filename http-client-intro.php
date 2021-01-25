
Laravel provides an expressive, minimal API arround the Guzzle HTTP client, allowing you to quickly make outgoing HTTP requests to communicate with other web apps.

Laravel's wrapper around Guzzle is focused on its most common use cases and a wonderful dev experience.

Before getting started, insure you have the Guzzle package installed as a dependency of your app. Laravel automatically includes it, but if you need to install it:

	<? composer require guzzlehttp/guzzle ?>


Making requests
================

To make requests, use the <? get, post, put, patch, delete ?> methods provided by the Http facade.

<?
	use Illuminate\Support\Facades\Http;

	// basic get request
	$response = Http::get('http://example.com');
?>

The get method returns an instance of Illuminate\Http\Client\Response, which provides a variety of methods that can be used to inspect the response:

<?
	$response->body() : string;
	$response->json() : array|mixed;
	$response->status() : int;
	$response->ok() : bool;
	$response->successful() : bool;
	$response->failed() : bool;
	$response->serverError() : bool;
	$response->clientError() : bool;
	$response->header($header) : string;
	$response->headers() : array;
?>

The Illuminate\Http\Client\Response object also implements the PHP ArrayAccess interface, allowing you access to JSON response data directly on the response:

<?
	return Http::get('http://example.com/users/1')['name'];
?>


Request Data
===============

Post, put, and patch requests requires additional data with your request so those methods accept an array of data as their 2nd argument.

By default, this data will be sent using to application/json content type.

<?
	$response = Http::post('http://example.com/users', [
	    'name' => 'Steve',
	    'role' => 'Network Administrator',
	]);
?>

Get requests also have this option:

<?
	$response = Http::get('http://example.com/users', [
	    'name' => 'Taylor',
	    'page' => 1,
	]);
?>

To send form URL encoded requests, call the asForm method to utilize the application/x-www-form-urlencoded content type.

<?
	$response = Http::asForm()->post('http://example.com/users', [
	    'name' => 'Sara',
	    'role' => 'Privacy Consultant',
	]);
?>

Use the withBody method to provide a raw request body when making a request. The content type can be provided via the method's 2nd argument:

<?
	$response = Http::withBody(
	    base64_encode($photo), 'image/jpeg'
	)->post('http://example.com/photo');
?>

If you need to send files a multi-part requests, call the attach method before making your request. This method accepts the name of the file and its contents. If needed, provide a 3rd argument which will be considered the file's filename:

<?
	$response = Http::attach(
	    'attachment', file_get_contents('photo.jpg'), 'photo.jpg'
	)->post('http://example.com/attachments');
?>



Headers
=============

Headers can be added to requests using the withHeaders method. This method accepts an array of key/value pairs:

<?
	$response = Http::withHeaders([
	    'X-First' => 'foo',
	    'X-Second' => 'bar'
	])->post('http://example.com/users', [
	    'name' => 'Taylor',
	]);
?>


Authentication 
================

You can specify basic and digest authentication credentials using the withBasicAuth or withDigestAuth methods:

<?
	// Basic authentication...
	$response = Http::withBasicAuth('taylor@laravel.com', 'secret')->post(...);

	// Digest authentication...
	$response = Http::withDigestAuth('taylor@laravel.com', 'secret')->post(...);

	// To quickly add bearer token to request's Authorization header...
	$response = Http::withToken('token')->post(...);
?>


Timeout
===========

The timeout method can be used to specify the max number of seconds to wait for a response:

<?
	// If given timeout exceeded, instance of Illuminate\Http\Client\ConnectionException thrown
	$response = Http::timeout(3)->get(...);
?>


Retries
===========

If you need the HTTP client to automatically retry the request if a client or server error occurs, you can use the retry method.

This method accept the max number of attempts as the 1st argument and the number of milliseconds to wait between attempts as the 2nd argument:

<?
	// If given timeout exceeded, instance of Illuminate\Http\Client\ConnectionException thrown
	$response = Http::retry(3, 100)->post(...);
?>


Error Handling
===============

Unlike Guzzle's default behavior, Laravel's HTTP client wrapper doesn't throw exceptions on client or server errors (400 & 500 level responses).

You can determine if one of these errors was returned using the successful, clientError, or serverError methods:

<?
	// Determine if the status code is >= 200 and < 300...
	$response->successful();

	// Determine if the status code is >= 400...
	$response->failed();

	// Determine if the response has a 400 level status code...
	$response->clientError();

	// Determine if the response has a 500 level status code...
	$response->serverError();
?>

If you have a response instance and want to throw an error instance of Illuminate\Http\Client\RequestException indicates a client or server error, use the throw method:

<?
	$response = Http::post(...);

	// Throw an exception if a client or server error occurred...
	$response->throw();

	return $response['user']['id'];
?>

The Illuminate\Http\Client\RequestException instance has a public $response property that will allow you to inspect the returned response:

The throw method returns the response instance if no error occurred, allowing you to chain other operations onto the throw method.

	<? return Http::post(...)->throw()->json(); ?>

If you need to perform additional logic before the exception is thrown, pass a closure to the throw mehod.

The exception will be thrown automatically after the closure is invoked so you don't need to re-throw the exception from within the closure:

<?
	return Http::post(...)->throw(function ($response, $e) {
	    //
	})->json();
?>



Guzzle Options
================

You can specify additional Guzzle request options using the withOptions method. It accepts an array of key/value pairs:

<?
	$response = Http::withOptions([
	    'debug' => true,
	])->get('http://example.com/users');
?>

