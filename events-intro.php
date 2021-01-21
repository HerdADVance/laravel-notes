
Laravel's events provide a simple observer pattern implementation that allows you to subscribe and listen for various events that occur within your application.

Event classes are typically stored in the app/Events directory, and their listeners are stored in the app/Listeners directory.

Events serve as a great way to decouple various aspects of your app since a single event can have multiple listeners that don't depend on each other.


Registering Events & Listeners
==============================

The App\Providers\EventServiceProvider included with Laravel is where you register your app's event listeners. 

The listen property contains an array of all events (keys) and their listeners (values).

You can add as many events to this array as needed. For example, let's add an OrderShipped event:

<?
	use App\Events\OrderShipped;
	use App\Listeners\SendShipmentNotification;

	protected $listen = [
	    OrderShipped::class => [
	        SendShipmentNotification::class,
	    ],
	];
?>


Generating Events & Listeners
=============================

Manually creating the files for each event and listerner is cumbersome. Instead, add listeners and events to your EventServiceProvider and use the event:generate Artisan command.

This command will generate any events or listeners that are listed in your EventServiceProvider that don't already exist:

	<? php artisan event:generate ?>

Alternatively, you can use the make:event and make:listener Artisan commands to generate individual events and listeners:

	<? php artisan make:event PodcastProcessed ?>
	<? php artisan make:listener SendPodcastNotification --event=PodcastProcessed ?>


Manually Registering Events
============================

Typically, events should be registered via the EventServiceProvider $listen array. But you can also register class or closure based event listeners manually in the boot method of the EventServiceProvider:

<?
	use App\Events\PodcastProcessed;
	use App\Listeners\SendPodcastNotification;
	use Illuminate\Support\Facades\Event;

	public function boot()
	{
	    Event::listen(
	        PodcastProcessed::class,
	        [SendPodcastNotification::class, 'handle']
	    );

	    Event::listen(function (PodcastProcessed $event) {
	        //
	    });
	}
?>


Queueable Anonymous Event Listeners
===================================

When registering closure based listeners manually, you can wrap the listener closure within the Illuminate\Events\Queueable function to tell Laravel to execute the listener using the queue:

<?
	use App\Events\PodcastProcessed;
	use function Illuminate\Events\queueable;
	use Illuminate\Support\Facades\Event;

	public function boot()
	{
	    Event::listen(queueable(function (PodcastProcessed $event) {
	        //
	    }));
	}
?>


Event Discovery
==================

Instead of registering events and listeners manually in the $listen array of the EventServiceProvider, you can enable automatic event discovery.

When this is enabled, Laravel will automatically find and register your events and listeners by scanning your app's Listeners directory. In addition, any explicitly defined events listed in the ESP will still be registered.

Laravel find events listeners by scanning the listener classes using PHP's reflection services. When Laravel finds any listener class method that begins with handle, it registers those methods as event listeners for the event that is type-hinted in the method's signature:

<?
	use App\Events\PodcastProcessed;

	class SendPodcastNotification
	{
	    public function handle(PodcastProcessed $event)
	    {
	        //
	    }
	}
?>

Event discovery is disabled by default. To turn it on, override the shouldDiscoverEvents method in your app's ESP:

<?
	public function shouldDiscoverEvents()
	{
    	return true;
	}
?>

To add additional directories to be scanned other than app/Listeners, override the discoverEventsWithin method in the ESP:

<?
	protected function discoverEventsWithin()
	{
	    return [
	        $this->app->path('Listeners'),
	    ];
	}
?>


Event Discovery in Production
==============================

In production, it's not efficient for the framework to scan all of your listeners on every request. 

Therefore, during your deployment process, you should run the event:cache Artisan command to cache a manifest of all of your app's listeners and events.

This manifest will be used by the framework to speed up the event registration process.

The event:clear command can be used to destroy the cache.






