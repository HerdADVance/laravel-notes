
Events are broadcast over channels which can be specified as public or private. A user must be authorized and authenticated to listen on a private channel.

In this example, we have a page that allwows users to view the shipping status for thir orders. A OrderShipmentStatusUpdated event is fired when a shipping status update is processed by the application.

<?
	use App\Events\OrderShipmentStatusUpdated;

	OrderShipmentStatusUpdated::dispatch($order);
?>

When a viewer is viewing one of their orders, we don't want to make them refresh the page to view status updates. Instead, we want to broadcast updates as they're created.

So we need to mark the OrderShipmentStatusUpdated event with the ShouldBroadcast interface. This instructs Laravel to broadcast the event when fired:

<?
	class OrderShipmentStatusUpdated implements ShouldBroadcast // adding ShouldBroadcast to event
	{
	    public $order;
	}
?>

The shouldBroadcast interface requires our event to define a broadcastOn method.

This method is reponsible for returning the channels that the event should be broadcast on.

An empty stub of this method is already defined on generated event classes so we only need fill in its details.

We only want the creator of the order to be able to view status updates so we broadcast the event on a private channel that is tied to the order:

<?
	public function broadcastOn()
	{
	    return new PrivateChannel('orders.'.$this->order->id); // channel will be named order.1 for example
	}
?>


Authorizing Channels
====================

Users must be authorized to listen on private channels. We define these rules in the routes/channels.php file.

In this example, we need to verify that any user attempting to listen to the private order.1 channnel is actually the creator of that order:

<?
	// routes/channel.php file
	use App\Models\Order;

	Broadcast::channel('orders.{orderId}', function ($user, $orderId) {
	    return $user->id === Order::findOrNew($orderId)->user_id;
	});
?>

The channel method above accepts two arguments: the name of the channel and a callback which returns true or false.

All authorization callbacks receive the currently authenticated user as their first argument and any additional wildcard parameters as their subsequent arguments.

In the example above, the {orderId} placeholder indicates that the ID portion of the channel name is a wildcard.


Listening for Event Broadcasts
==============================

Now all we have to do is listen for the event in our JS app with Laravel Echo.

First, we use the private method to subscribe to the private channel.

Then we use the listen method to listen for our OrderShipmentStatusUpdated event.

By dedault, all of the event's public properties will be included on the broadcast event.

<script>
	Echo.private(`orders.${orderId}`)
	    .listen('OrderShipmentStatusUpdated', (e) => {
	        console.log(e.order);
	    });
</script>
