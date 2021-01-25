
Client Events
===============

When using Pusher Channels, you must enable the Client Events option in the App Settings section of your app dashboard in order to send client events.

To broadcast an event to other connected clients without hitting your Laravel app at all (useful for things like typing notifications), use Echo's whisper method.

<script>
	Echo.private(`chat.${roomId}`)
	    .whisper('typing', {
	        name: this.user.name
	    });

	// or listenForWhisper to listen for client events
	Echo.private(`chat.${roomId}`)
	    .listenForWhisper('typing', (e) => {
	        console.log(e.name);
	    });
</script>


Notifications
================

By pairing event broadcasting with notifications, your JS app can receive new notifications as the occur without needing to refresh the page.

Once you've configured a notification to use the broadcast channel, listen for the broadcast events using Echo's notification method.

The channel name should match the class name of the entity receiving the notifications:

<script>
	Echo.private(`App.Models.User.${userId}`)
	    .notification((notification) => {
	        console.log(notification.type);
	    });
</script>

In the above example, all notiications sent to App\Models\User instances via the broadcast channel would be received by the callback.

A channel authorization callback for the App.Models.User.{id} channel is included in the default BroadcastServiceProvider that ships with Laravel.