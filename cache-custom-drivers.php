
Adding Custom Cache Drivers
==============================

Put your custom cache driver code in a created Extensions namespace within the App directory.

To create a custom cache driver, we need to implement the Illuminate\Contacts\Cache\Store contract.

A MongoDB implementation might look something like this:

<?
	namespace App\Extensions;

	use Illuminate\Contracts\Cache\Store;

	class MongoStore implements Store
	{
	    public function get($key) {}
	    public function many(array $keys) {}
	    public function put($key, $value, $seconds) {}
	    public function putMany(array $values, $seconds) {}
	    public function increment($key, $value = 1) {}
	    public function decrement($key, $value = 1) {}
	    public function forever($key, $value) {}
	    public function forget($key) {}
	    public function flush() {}
	    public function getPrefix() {}
	}
?>

We just need to implement each of these methods using a MongoDB connection. For an example of how to implement each of these methods, take a look at the Illuminate\Cache\MemcachedStore in the Laravel source code.

Once our implementation is complete, we can finish our custom driver registration by calling the Cache facade's extend method:

<?
	Cache::extend('mongo', function ($app) {
	    return Cache::repository(new MongoStore);
	});
?>


To register the custom cache driver with Laravel, use the extend method on the Cache facade.

Since other service providers may attempt to read cached values within their boot method, we'll register our custom driver within a booting callback.

By using this callback, we can ensure that the custom driver is registered just before the boot method is called on our app's service providers but also after the register method is called on all of them.

We'll register our booting callback within the register method of our app's App\Providers\AppServiceProvider class:

<?
	namespace App\Providers;

	use App\Extensions\MongoStore;
	use Illuminate\Support\Facades\Cache;
	use Illuminate\Support\ServiceProvider;

	class CacheServiceProvider extends ServiceProvider
	{
	    public function register()
	    {
	        $this->app->booting(function () {
	             Cache::extend('mongo', function ($app) {
	                 return Cache::repository(new MongoStore);
	             });
	         });
	    }

	    public function boot()
	    {
	        //
	    }
	}
?>

The 1st argument passed to the extend method above is the name of the driver. This will correspond to your driver option in the config/cache.php config file.

The 2nd argument is a closure that should return an Illuminate\Cache\Repository instance. The closure will be passed an $app instance, which is an instance of the service container.

Once your extension is registered, update your config/cache.php config file's driver option to the name of your extension.


