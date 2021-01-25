
To execute code on every cache operation, you can listen for the events fired by the cache.

Typically, you should place these event listeners in your app's App\Providers\EventServiceProvider class:

<?
	protected $listen = [
	    'Illuminate\Cache\Events\CacheHit' => [
	        'App\Listeners\LogCacheHit',
	    ],

	    'Illuminate\Cache\Events\CacheMissed' => [
	        'App\Listeners\LogCacheMissed',
	    ],

	    'Illuminate\Cache\Events\KeyForgotten' => [
	        'App\Listeners\LogKeyForgotten',
	    ],

	    'Illuminate\Cache\Events\KeyWritten' => [
	        'App\Listeners\LogKeyWritten',
	    ],
	];
?>