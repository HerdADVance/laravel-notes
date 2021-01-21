Getting Setup
-------------
1. config/broadcasting.php - change setting and input Pusher keys
2. config/app.php - uncomment broadcast service provider
3. .env - change BROADCAST_DRIVER to pusher
4. Echo needs CSRF token as meta tag in Head of HTML
5. Install Pusher: composer require pusher/pusher-php-server "~4.0"
6. resources/js/bootstrap.js - add below code

import Echo from "laravel-echo";

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'your-pusher-channels-key'
});

7. Might need to configure and run a queue listener? (config/queue.php)



laravel-websockets package
--------------------------
- Allows full power of Laravel broadcasting without an external websocket provider
- Compatible with Pusher
- Doesn't require Pusher?


Basic Usage Example
-------------------
1. Implement ShouldBroadcast interface
2. This interface requires a broadcastOn method
3. This broadcastOn method returns new PublicChannel or new PrivateChannel
4. routes/channels.php - where we verify who can listen to private channels

Broadcast::channel('order.{orderId}', function ($user, $orderId) {
    return $user->id === Order::findOrNew($orderId)->user_id;
});

The above method accepts two arguments: 
	- name of the channel
	- callback which returns true or false, indicating if user can listen to channel

The callback mentioned above receives the following arguments:
	- currently authenticated user as first argument
	- any additional wildcards parameters as subsequent arguments

5. Use Echo to listern for event:

Echo.private(`order.${orderId}`)
    .listen('ShippingStatusUpdated', (e) => {
        console.log(e.update);
    });

By default, all of the event's public properties will be included as serialized payload




broadcastAs method
------------------
Use if want to broadcast with something other than event's class name.
If doing this, register Echo listener with a prepended .

broadcastWith method
--------------------
Use this if you want to control which data from your model gets sent in the payload.
Specify the array of data you want to broadcast: return ['id' => $this->user->id];

broadcastWhen method
--------------------
Use this to broadcast only when a given condition is true: return $this->value < 100;



