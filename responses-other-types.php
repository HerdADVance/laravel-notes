
The response helper may be used to generate other types of response instances. 

When it's called without arguments, an implementation of the Illuminate\Contracts\Routing\ResponseFactory contract is returned.

This contract provides several helpful methods for generating responses.

View Method
============

If you need control over the response's status and headers but also need to return a view as the response's content, use the view method:

<?
	return response()
		->view('hello', $data, 200)
    	->header('Content-Type', $type);
?>


JSON Method
===========

The json method will automatically set the Content-Type header to application/json and convert the given array to JSON using the json_encode PHP function:

<?
	return response()->json([
    	'name' => 'Abigail',
    	'state' => 'CA',
	]);
?>

You create a JSONP (JSON with padding - used to avoid CORS problems) response, use the json method in combination with the withCallback method:

<?
	return response()
		->json(['name' => 'Abigail', 'state' => 'CA'])
		->withCallback($request->input('callback'));
?>

Download & Streamed Downloads Methods
=====================================

The download method may be used to generate a response that forces the user's browser to download the file at the given path. 

It accepts a filename as the 2nd argument to the method which determines the filename that is seen by the user downloading the file. 

An array of HTTP headers can be the 3rd argument.

<?
	return response()->download($pathToFile);

	return response()->download($pathToFile, $name, $headers);
?>

To turn the string response of a given operation into a downloadable response without having to write the contents of the operation to disk, use the streamDownload method. 

It accepts a callback, filename, and optional array of headers as arguments:

<?
use App\Services\GitHub;

	return response()->streamDownload(function () {
	    echo GitHub::api('repo')
	    	->contents()
	    	->readme('laravel', 'laravel')['contents'];
	}, 'laravel-readme.md');
?>


File Method
===================

The file method may be used to display a file, such as image or PDF, directly in the user's browser instead of initiating a download.

This method accepts the path to the file as the 1st argument and an array of headers as the 2nd one:

<?
	return response()->file($pathToFile);

	return response()->file($pathToFile, $headers);
?>


Response Macros
===================

If you want to define a custom response that can be re-used in a variety of routes and controllers, use the macro method from the Response facade.

This method should typically be called from the boot method of one of your app's service providers such as the App\Providers\AppServiceProvider.

The macro function accepts a name as its 1st argument and a closure as its 2nd. 

<?
	namespace App\Providers;

	use Illuminate\Support\Facades\Response;
	use Illuminate\Support\ServiceProvider;

	class AppServiceProvider extends ServiceProvider
	{
	    public function boot()
	    {
	        Response::macro('caps', function ($value) {
	            return Response::make(strtoupper($value));
	        });
	    }
	}
?>

The closure will be executed when calling the macro from a ResponseFactory implementation or the response helper:

<?
	return response()->caps('foo');
?>

