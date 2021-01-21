
Terminable Middleware
=====================

Sometimes you might need to do some work after the response has been sent to the browser. This is done through a terminate method in the middleware. 

The terminate method will automatically be called after the response is sent to the browser (if your web server is using FastCGI).

<?
	namespace Illuminate\Session\Middleware;

	use Closure;

	class TerminatingMiddleware
	{
	    public function handle($request, Closure $next)
	    {
	        return $next($request);
	    }

	    public function terminate($request, $response)
	    {
	        // ...
	    }
	}
?>

Note that the terminate method receives both a request and response.

Laravel resolves a fresh instance of the middleware from the service container when calling the terminate method. 

If you need to use the same instance for both the handle and terminate methods, register the middleware with the container through the container's singleton method in the AppServiceProvider:

<?
	use App\Http\Middleware\TerminatingMiddleware;

	public function register()
	{
	    $this->app->singleton(TerminatingMiddleware::class);
	}
?>