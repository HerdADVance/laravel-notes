
Rate Limiting
==============

This should be done within the configureRateLimiting method of the RouteServiceProvider

The RateLimiter facade's for method is used. It accepts a rate limiter name and a closure that returns the limit configuration that should apply to routes assigned to the limiter.

The limit configurations are instances of the Illuminate\Cache\RateLimiting\Limit class. It contains helpful builder methods so you can quickly define your limit.

<?
	use Illuminate\Cache\RateLimiting\Limit;
	use Illuminate\Support\Facades\RateLimiter;

	protected function configureRateLimiting()
	{
	    RateLimiter::for('global', function (Request $request) {
	        return Limit::perMinute(1000);
	    });
	}
?>

If an incoming request exceeds the limit, a 429 response is automatically returned. Use the response method if you want to define your own response:

<?
	RateLimiter::for('global', function (Request $request) {
	    return Limit::perMinute(1000)->response(function () {
	        return response('Custom response...', 429);
	    });
	});
?>

You can make the limit dynamic based on the incoming request or authenticated user:

<?
	RateLimiter::for('uploads', function (Request $request) {
	    return $request->user()->vipCustomer()
            ? Limit::none()
            : Limit::perMinute(100);
	});
?>

The throttle middleware can be used to attach rate limiters to routes or route groups:

<?
	Route::middleware(['throttle:uploads'])->group(function () {
	    Route::post('/audio', function () {
	        //
	    });

	    Route::post('/video', function () {
	        //
	    });
	});
?>