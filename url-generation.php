
Laravel provides several helpers to assist you in generating URLs for your application. These helpers are primarily helpful when building links in your templates and API responses, or when generating redirect responses to another part of the app.

The url helper is used to generate arbitrary URLs for your app. The generated URL will automatically use the scheme (HTTP or HTTPS) and host from the current request being handled by the app:

<?
	$post = App\Models\Post::find(1);

	// http://example.com/posts/1
	echo url("/posts/{$post->id}");
?>

If no path is provided to the url helper, an Illuminate\Routing\UrlGenerator instance is returned, which allows you to access info about the current URL:

<?
	// Get the current URL without the query string...
	echo url()->current();

	// Get the current URL including the query string...
	echo url()->full();

	// Get the full URL for the previous request...
	echo url()->previous();
?>

Each of these methods can also be accessed via the URL facade:
<?
	use Illuminate\Support\Facades\URL;

	echo URL::current();
?>


URLs for Named Routes
======================

The route helper can be used to generate URLs to named routes. This way they don't have to be coupled to a URL that may or may not change:

<?
	// route file
	Route::get('/post/{post}', function () {})->name('post.show');

	// echoing its URL elsewhere
	echo route('post.show', ['post' => 1]); // http://example.com/post/1
?>

The route helper can also be used for routes with multiple parameters:

<?
	//route file
	Route::get('/post/{post}/comment/{comment}', function () {})->name('comment.show');

	// echoing its URL elsewhere
	echo route('comment.show', ['post' => 1, 'comment' => 3]); // http://example.com/post/1/comment/3
?>

Any additional elements that don't correspond to the route's definition parameters will be added to the URL's query string:

<?
	echo route('post.show', ['post' => 1, 'search' => 'rocket']); // http://example.com/post/1?search=rocket
?>

You'll often be generating URL's using the primary key of Eloquent models. You can pass an Eloquent model as parameter values. The route helper will automatically extract the model's primary key:

<?
	echo route('post.show', ['post' => $post]); // http://example.com/post/{dynamic ID from model}
?>


Signed URLs
=====================

Laravel allows you to easily create signed URLs to named routes. These URLs have a signature hash appended to the query string which allows Laravel to verify that the URL hasn't been modified since it was created.

Signed URLs are especially useful for routes that are publicly accessible yet need a layer of protection against URL manipulation.

For example, you could use them to implement a public unsubscribe link that is emailed to your customers. To create a signed URL to a named route, use the signedRoute method of the URL facade:

<?
	use Illuminate\Support\Facades\URL;

	return URL::signedRoute('unsubscribe', ['user' => 1]);
?>

To create a temporary signed route URL that expires after a specified amount of time, use the temporarySignedRoute method.

When Laravel validates a temporary signed route URL, it ensures that the expiration timestampe that's encoded into the signed URL has not elapsed:

<?
	use Illuminate\Support\Facades\URL;

	return URL::temporarySignedRoute(
	    'unsubscribe', now()->addMinutes(30), ['user' => 1]
	);
?>

To verify that an incoming request has a valid signature, call the hasValidSignature method on the incoming Request:

<?
use Illuminate\Http\Request;

	Route::get('/unsubscribe/{user}', function (Request $request) {
	    if (! $request->hasValidSignature()) {
	        abort(401);
	    }

	    // ...
	})->name('unsubscribe');
?>

Alternatively, you can assign the Illuminate\Routing\Middleware\ValidateSignature middleware to the route. If it's not already present, assign this middleware as a key in your kernel's routeMiddleware array:

<?
	protected $routeMiddleware = [
	    'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
	];
?>

Once you've registered the middleware in your kernel, you can attach it to a route. If the incoming request doesn't have a valid signature, the middleware will automatically return a 403 response:

<?
	Route::post('/unsubscribe/{user}', function (Request $request) {
	    // ...
	})->name('unsubscribe')->middleware('signed');
?>


URLs for Controller Actions
===========================

The action function generates a URL for the given controller action:

<?
	use App\Http\Controllers\HomeController;

	$url = action([HomeController::class, 'index']);
?>

If the controller method accepts route parameters, you can pass an associative array of route parameters as the 2nd argument to the function:

<?
	$url = action([UserController::class, 'profile'], ['id' => 1]);
?>


Default Values
==================

For some applications, you may wish to specify request-wide default values for certain URL parameters. For example, imagine many of your routes define a {locale} parameter:

<?
	Route::get('/{locale}/posts', function () {// })->name('post.index');
?>

It's cumbersome to pass the "locale" every time you call the route helper. Instead, you can use the URL::defaults method to define a default value for this parameter that will always be applied during the current request.

You may wish to call this method from a route middleware so that you have access to the current request:

<?
	namespace App\Http\Middleware;

	use Closure;
	use Illuminate\Support\Facades\URL;

	class SetDefaultLocaleForUrls
	{
	    public function handle($request, Closure $next)
	    {
	        URL::defaults(['locale' => $request->user()->locale]);

	        return $next($request);
	    }
	}
?>

Once the default value for the locale parameter has been set, you're no longer required to pass its value when generating URLs via the route helper.


URL Defaults and Middleware Priority
=====================================

Setting URL default values can interfere with Laravel's handling of implicit model bindings. Therefore, you should prioritize your middleware that set URL defaults to be executed before Laravel's own SubstituteBindings middleware.

You can accomplish this by making sure your middleware occurs before the SubstituteBindings middleware within the $middlewarePriority property of your app's kernel.

The $middlewarePriority property is defined in the base kernel class. You can copy its definition from that class and overwrite it in your app's kernel in order to modify it:

<?
	protected $middlewarePriority = [
	    // ...
	     \App\Http\Middleware\SetDefaultLocaleForUrls::class,
	     \Illuminate\Routing\Middleware\SubstituteBindings::class,
	     // ...
	];
?>

