
Dispatching Events
====================

To dispatch an event, call the static dispatch method on the event.

This method is made available by the Illuminate\Foundation\Events\Dispatchable trait.

Any arguments passed to the dispatch method will be passed to the event's constructor:

<?
	namespace App\Http\Controllers;

	use App\Events\OrderShipped;
	use App\Http\Controllers\Controller;
	use App\Models\Order;
	use Illuminate\Http\Request;

	class OrderShipmentController extends Controller
	{
	    public function store(Request $request)
	    {
	        $order = Order::findOrFail($request->order_id);

	        // Order shipment logic...

	        OrderShipped::dispatch($order);
	    }
	}
?>


Event Subscribers
==================

Event subscribers are classes that may subscribe to multiple events from within the subscriber class itself. This allows you to define several event handlers within a single class.

Subscribers should define a subscribe method which will be passed an event dispatcher instance.

You can call the listen method on the given dispatcher to register event listeners.

<?
	namespace App\Listeners;

	class UserEventSubscriber
	{
	    public function handleUserLogin($event) {}

	    public function handleUserLogout($event) {}

	    public function subscribe($events)
	    {
	        $events->listen(
	            'Illuminate\Auth\Events\Login',
	            [UserEventSubscriber::class, 'handleUserLogin']
	        );

	        $events->listen(
	            'Illuminate\Auth\Events\Logout',
	            [UserEventSubscriber::class, 'handleUserLogout']
	        );
	    }
	}
?>


Registering Event Subscribers
=============================

After writing the subscriber, you're ready to register it with the event dispatcher.

You can register subscribers using the $subscribe property on the ESP.

For example, let's add the UserEventSubscriber to the list:

<?
	namespace App\Providers;

	use App\Listeners\UserEventSubscriber;
	use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

	class EventServiceProvider extends ServiceProvider
	{
	    protected $listen = [
	        //
	    ];

	    protected $subscribe = [
	        UserEventSubscriber::class,
	    ];
	}
?>



