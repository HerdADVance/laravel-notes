
Once you've defined an event and marked it with the shouldBroadcast interface, you only need to fire the event using its dispatch method.

The event dispatch will notice that the event is marked with the ShouldBroadcast interface and will queue the event for broadcasting:

<?
	use App\Events\OrderShipmentStatusUpdated;

	OrderShipmentStatusUpdated::dispatch($order));
?>

Only to Others
================

When building an application that utilizes event broadcasting, you might want to broadcast an event to all subscribers to a given channel except for the current user.

Do this using the broadcast helper and the toOthers method:

<?
	use App\Events\OrderShipmentStatusUpdated;

	broadcast(new OrderShipmentStatusUpdated($update))->toOthers();
?>

Your event must use the Illuminate\Broadcasting\InteractsWithSockets trait in order to use the toOthers method.

When you initilize an Echo instance, a socket ID is assigned to the connection.

If you're using a global Axios instance to make HTTP requests from your JS application, the socket ID will automatically be attached to every outgoing request as a X-Socket-ID header.

When you call the toOthers method, Laravel will extract the socket ID from the header and instruct the broadcasters to not broadcast any connections with that socket ID.

If you're not using a global Axios instance, you need to manually configure your JS app to send the X-Socket-ID header with all outgoing requests. Do this with the Echo.socketId method:

<script>
	var socketId = Echo.socketId();
</script>



Receiving Broadcasts
======================

Once you've installed Echo, you can start listening for events that are broadcast from your Laravel app.

Use the channel method to retrieve an instance of a channel and then call the listen method to listen to a specified event:

<script>
	Echo.channel(`orders.${this.order.id}`)
	    .listen('OrderShipmentStatusUpdated', (e) => {
	        console.log(e.order.name);
	    });
</script>

For a private channel, use the private method instead. You can continue to chain calls to the listen method if you want to listen for multiple events on a single channel:

<script>
	Echo.private(`orders.${this.order.id}`)
    	.listen(...)
    	.listen(...)
    	.listen(...);
</script>


Leaving a Channel
===================

To leave a channel, use the leaveChannel method on your Echo instance:

<script>
	// The leaveChannel method leave just that channel
	Echo.leaveChannel(`orders.${this.order.id}`);

	// The leave method will also leave the associated private and presence channels
	Echo.leave(`orders.${this.order.id}`);
</script>


Namespaces
============

In the examples above, we didn't specify the full App\Events namespace for the event classes. That's because Echo will automatically assume the events are located in that namespace.

You can configure the root namespace when you instantiate Echo by passing a namespace config option:

<script>
	window.Echo = new Echo({
	    broadcaster: 'pusher',
	    // ...
	    namespace: 'App.Other.Namespace'
	});
</script>

Or you can prefix event classes with a dot when subscribing to them.

<script>
	Echo.channel('orders')
	    .listen('.Namespace\\Event\\Class', (e) => {
	        //
	    });
</script>


