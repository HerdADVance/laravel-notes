
In many modern web apps, WebSockets are used to implement realtime, live-updating user interfaces. When some data is updated on a server, a message is typically sent over a WebSocket connection to be handled by the client. 

Websockets provide a more efficient alternative to continually polling your app's erver for data changes that need to be reflected in your UI.

To assist with these features, Laravel makes it easy to broadcast your server-side Laravel events over a WebSocket connection. 

Doing this allows you to share the same event names and data between your server-side Laravel app and your client-side JS app


Supported Drivers
=================

Laravel includes two server-side broadcasting drivers by default for you to choose from: Pusher Channels and Ably. 

However, community driven packages such as laravel-websockets provide additional broadcasting drivers that don't require commercial broadcasting providers.


Server Side Installation
=========================

To get started with Laravel's event broadcasting, you need to do some configuration within the Laravel app and install a few packages.

Event broadcasting is accomplished by a server-side broadcasting driver that broadcasts your Laravel events so that Laravel Echo (JS library) can recieve them from the browser client.

All of your app's broadcasting config is stored in the config/broadcasting.php file.

Laravel supports several broadcast drivers out of the box: Pusher Channels, Redis, and a log driver for local development and debugging. A null driver is also included which allows you to disable broadcasting during testing. A config example for each of these options is included in the broadcasting.php file.


Broadcast Service Provider
============================

Before broadcasting any events, you need to regiter the App\Providers\BroadcastServiceProvider. 

In new Laravel apps, you only need to uncomment that provider in the providers array of the config/app.php file. 

This BSP contains the code necessary to register the broadcast authorization routes and callbacks.


Queue Configuration
====================

You'll also need to configure and run a queue worker. All event broadcasting is done via queued jobs so that the response time of your application is not seriously affected by events being broadcast.


Pusher Channels
=================

If you plan use Pusher you need to install it through Composer:

	<? composer require pusher/pusher-php-server "~4.0" ?>

Then you need to configure Pusher Channels credentials in the broadcasting.php file. An example config is already included so just replace your APP_ID, APP_KEY, APP_SECRET, and APP_CLUSTER values as needed.

You can also set additional options that are supported by Channels such as the cluster in the broadcasting.php pusher config.

You also need to change the broadcast driver to pusher in your .env file

	<? BROADCAST_DRIVER=pusher ?>

Finally, install and configure Laravel Echo which will receive the broadcast events on the client side.


Ably
============

If you plan to use Ably, first install it through Composer:

	<? composer require ably/ably-php ?>

Next, switch the ABLY_KEY credential in the broadcasting.php file just as you would have with Pusher.

And change the broadcast driver to ably in the .env file:

	<? BROADCAST_DRIVER=ably ?>

As with Pusher, also install and configure Laravel Echo.


Open-Source Alternative: Laravel Websockets Package
===================================================

The laravel-websockets package is a pure PHP, Pusher-compatible WebSocket package for Laravel. It allows you to leverage the full power of broadcasting without a commercial WebSocket provider. Visit its docs for more detail.






