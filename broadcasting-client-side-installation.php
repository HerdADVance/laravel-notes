
Laravel Echo is a JS library that makes it painless to subscribe to channels and listen for events broadcast by your server-side broadcasting driver.

Pusher
=============

You can install Echo via the NPM package manager. In this example, we'll also isntall the pusher-js package:

	<? npm install --save-dev laravel-echo pusher-js ?>

Once Echo is installed, you can create a fresh Echo instance in your app's JS. A good place to do that is at the bottom of the resources/js/bootstrap.js file that's included in Laravel. 

By default, an example Echo config is already included in this file so you just need to uncomment it.

<script>
	import Echo from 'laravel-echo';

	window.Pusher = require('pusher-js');

	window.Echo = new Echo({
	    broadcaster: 'pusher',
	    key: process.env.MIX_PUSHER_APP_KEY,
	    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
	    forceTLS: true
	});
</script>

Once you've done that, run <? npm run dev ?> to compile your app's assets.

If you already have a pre-configured Pusher Channels client instance that you want Echo to utilize, pass it to Echo via the client config option:

<script>
	import Echo from 'laravel-echo';

	const client = require('pusher-js');

	window.Echo = new Echo({
	    broadcaster: 'pusher',
	    key: 'your-pusher-channels-key',
	    client: client
	});
</script>


Ably
===========

With Ably, you actually also install the pusher-js package as in the 1st example of this file. Ably includes a Pusher compatability mode which lets us use the Pusher protocol when listening for events in our client-side app.

You need to enable Pusher protocol suppoer in your Ably app settings. You can enable this feature within the "Protocol Adapter Settings" portion of Ably's settings dashboard.

The bootstrap.js file would be configured like this for Ably:

<script>
	import Echo from 'laravel-echo';

	window.Pusher = require('pusher-js');

	window.Echo = new Echo({
	    broadcaster: 'pusher',
	    key: process.env.MIX_ABLY_PUBLIC_KEY,
	    wsHost: 'realtime-pusher.ably.io',
	    wsPort: 443,
	    disableStats: true,
	    encrypted: true,
	});
</script>

The MIX_ABLY_PUBLIC_KEY value should be your Ably public key that occurs before the :