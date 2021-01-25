
Obtaining a Cache Instance
===========================

To obtain a cache store instance, use the Cache facade. It provides convenient, terse access to the underlying implementations of the Laravel cache contracts:

<?
namespace App\Http\Controllers;

	use Illuminate\Support\Facades\Cache;

	class UserController extends Controller
	{
	    public function index()
	    {
	        $value = Cache::get('key');

	        //
	    }
	}
?>

Using the Cache facade, you can access various cache stores via the store method.

The key passed to the store method should correspond to one of the stores listed in the stores configuration array in your cache config file:

<?
	$value = Cache::store('file')->get('foo');

	Cache::store('redis')->put('bar', 'baz', 600); // 10 Minutes
?>


Retrieving Items From the Cache
================================

The Cache facade's get method is used to retrieve items from the cache.

If the item doesn't exist in the cache, null will be returned. You can pass a 2nd argument to the get method specifying the default value to be returned if the item doesn't exist:

<?
	$value = Cache::get('key');

	$value = Cache::get('key', 'default');
?>

You can also pass a closure as the default value. The result of the closure will be returned if the specified item doesn't exist in the cache.

Passing a closure allows you to defer the retrieval of default values from a DB or other external service.

<?
	$value = Cache::get('key', function () {
	    return DB::table(...)->get();
	});
?>

The has method can be used to determine if an item exists in the cache. It will return false if the item exists but the value is null:

<?
	if (Cache::has('key')) {
    	//
	}
?>

The increment and decrement methods can be used to adjust the value of integer items in the cache.

Both of these methods accept an optional 2nd argument indicating the amount by which to inc or dec the item's value:

<?
	Cache::increment('key');
	Cache::increment('key', $amount);
	Cache::decrement('key');
	Cache::decrement('key', $amount);
?>

The remember method will retrieve an item from the cache but also store a default value if the requested item doesn't exist.

If the item doesn't exist in the cache, the closure passed to the remember method will be executed and its result will be placed in the cache.

<?
	$value = Cache::remember('users', $seconds, function () {
	    return DB::table('users')->get();
	});

	// Or, the rememberForevermethod will, well, store it forever
	$value = Cache::rememberForever('users', function () {
	    return DB::table('users')->get();
	});
?>

The pull method will retrieve an item from the cache and then delete the item:

<?
	$value = Cache::pull('key');
?>



Storing Items in the Cache
===========================

You can use the put method on the Cache facade to store items in the cache:

<?
	Cache::put('key', 'value', $seconds = 10);

	// Without 3rd arg, item will be stored indefinitely
	Cache::put('key', 'value');

	// can also make DateTime instance the 3rd arg
	Cache::put('key', 'value', now()->addMinutes(10));
?>

The add method will only add the item to the cache if it doesn't already exist in the cache store. It returns true if the item is actually added to the cache, otherwise false.

<?
	Cache::add('key', 'value', $seconds);
?>

The forever method permanently stores an item in the cache. They can only be removed by the forget method (or when the cache reaches its size limit).

<?
	Cache::forever('key', 'value');
?>



Removing Items from the Cache
=============================

Use the forget method to remove items from the cache:

<?
	Cache::forget('key');

	// Or a zero or negative number in the put method
	Cache::put('key', 'value', 0);
	Cache::put('key', 'value', -5);

	// Clear the entire cache with flush method
	// Should be used carefully. Does not respect configured cache prefix and removes all entries
	Cache::flush();
?>



Cache Helper Function
=====================

In addition to using the Cache facade, you can also use the global cache helper function to retrieve and store data via the cache.

When the cache helper function is called with a single, string argument, it will return the value of the given key:

<?
	$value = cache('key');

	// Store values in the cache for specified duration
	cache(['key' => 'value'], $seconds);
	cache(['key' => 'value'], now()->addMinutes(10));

?>

When the cache helper function is called with no arguments, it returns an instance of the Illuminate\Contracts\Cache\Factory implementation which allows you to call other caching methods:

<?
	cache()->remember('users', $seconds, function () {
	    return DB::table('users')->get();
	});
?>

