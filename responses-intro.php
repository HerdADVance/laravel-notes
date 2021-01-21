
All routes and controllers should return a response to be sent back to the user's browser.

Laravel provides several different ways to return resposnes. The most basic is returning a string from a route or conroller.

The framework will automatically convert what is returned into a full HTTP response:

<?
	Route::get('/', function () {
    	return 'Hello World';
	});
?>


Of course, most of the time you'll be returning full instances of the Response class, or view.

Returning a full Response instance allows you to customize the response's HTTP status code and headers. 

A Response instance inherits from the Symfony\Component\HttpFoundation\Response class, which provides a variety of methods for building HTTP responses.

<?
	Route::get('/home', function () {
    	return response('Hello World', 200)
        	->header('Content-Type', 'text/plain');
	});
?>



You can also return Eloquent models and collections that will be automatically converted to JSON while respecting the model's hidden attributes.

<?
	Route::get('/user/{user}', function (User $user) {
    	return $user;
	});
?>




