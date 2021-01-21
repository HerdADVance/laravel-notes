
Defining Events
=================

An events class is essentially a data container which holds info related to the event.

Let's assume App\Events\OrderShipped event receives an Eloquent ORM object:

<?

	namespace App\Events;

	use App\Models\Order;
	use Illuminate\Broadcasting\InteractsWithSockets;
	use Illuminate\Foundation\Events\Dispatchable;
	use Illuminate\Queue\SerializesModels;

	class OrderShipped
	{
	    use Dispatchable, InteractsWithSockets, SerializesModels;

	    public $order;

	    public function __construct(Order $order)
	    {
	        $this->order = $order;
	    }
	}
?>

This event class contains no logic. It's just a container for the App\Models\Order instance that was purchased by the user. 

The SerializesModels trait used by the event will gracefully serialize any Eloquent models if the event object is serialized using PHP's serialize function, such as when utilizing queued listeners.


Defining Listners
===================

Event listeners receive event instances in their handle method.

The event:generate and make:listener Artisan commands will automatically import the proper event class and type-hint the event on the handle method.

Within this handle method, you can perform any actions necessary to respond to the event:

<?
	namespace App\Listeners;

	use App\Events\OrderShipped;

	class SendShipmentNotification
	{
	    public function __construct()
	    {
	        //
	    }

	    public function handle(OrderShipped $event)
	    {
	        // Access the order using $event->order...
	    }
	}
?>

You can return false on the above handle method to stop propagation of an event to other listeners.

