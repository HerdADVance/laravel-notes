
Presence channels build on the security of private channels while exposing the additional feature of awareness of who is subscribed to the channel.

This allows you to do things like notify users when another user is viewing the same page or list the inhabitants of a chatroom.


Authorizing Presence Channels
==============================

All presence channels are also private channels so users must be authorized to access them.

However, when defining authorization callbacks for presence channels, you won't return true if the user is authorized to join the channel. Instead, return an array of data about the user.

The data returned by the authorization callback will be made available to the presence channel event listeners in your JS app. If the user is not authorized to join the presence channel, return false or null:

<?
	Broadcast::channel('chat.{roomId}', function ($user, $roomId) {
	    if ($user->canJoinRoom($roomId)) {
	        return ['id' => $user->id, 'name' => $user->name];
	    }
	});
?>


Joining Presence Channels
==========================

To join a presence channel, use Echo's join method. This method will return a presenceChannel implementation which, along with exposing the listen method, allows you to subscribe to the here, joining, and leaving events.

<script>
	Echo.join(`chat.${roomId}`)
	    .here((users) => {
	        //
	    })
	    .joining((user) => {
	        console.log(user.name);
	    })
	    .leaving((user) => {
	        console.log(user.name);
	    });
</script>

The here callback will be executed immediately when the channel is successfully joined. It will also receive an array containing the user info for all other users currently subsscribed to the channel.

The joining method will be executed when a new user joins the channel, and the leaving method when a user leaves.


Broadcasting to Presence Channels
==================================

Presence channels can receive events just like public or private channels.

Using the example of a chatroom, we may want to broadcast NewMessage events to the room's presence channel. To do so, we'll return an instance of PresenceChannel from the event's broadcastOn method:

<?
	public function broadcastOn()
	{
	    return new PresenceChannel('room.'.$this->message->room_id);
}
?>

As with other events, you can use the broadcast helper and the toOthers method to exclude the current user from receiving the broadcast:

<?
	broadcast(new NewMessage($message));

	broadcast(new NewMessage($message))->toOthers();
?>

And as with other events, you can listen to events sent to presence channels using Echo's listen method:

<script>
	Echo.join(`chat.${roomId}`)
	    .here(...)
	    .joining(...)
	    .leaving(...)
	    .listen('NewMessage', (e) => {
	        //
	    });
</script>




