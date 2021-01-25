
Some of the data retrieval or processing tasks performed by your app could be CPU intensive or take several seconds to complete. When this is the case, it's common to cache the retrieved data for a time so it can be retrieved quickly on subsequent requests for the same data.

The cached data is usually stored in a very fast data store such as Memcached or Redis.

Laravel provides an expressive, unified API for various cache backends that allow you to take advantageof their blazing fast data retrieval and speed up your web app.


Configuration
===============

Your app's cache config file is located at config/cache.php. In this file, you specify which cache driver you would like to be used by default throughout your app.

Laravel supports popular caching backends like Memcached, Redis, DynamoDB, and relational DB's out of the box.

A file based cache driver is also available while array and null cache drivers provide convenient cache backends for your automated tests.

The cache config file also contains various other options that are documented within the file. By default, Laravel is configured to use the file cache driver which stores the serialized cached objects on the server's filesystem.

For larger apps, it's recommended that you use a more robust driver such as Memcached or Redis. You can also configure multiple cache configurations for the same driver.


Driver Prerequisites
=====================

Database
=========

When using the database cache driver, setup a table to contain the cache items. The <? php artisan cache:table ?> command will generate a migration with the proper schema:

<?
	Schema::create('cache', function ($table) {
	    $table->string('key')->unique();
	    $table->text('value');
	    $table->integer('expiration');
	});
?>


Memcached
===========

Using the Memcached driver requires the Memcached PECL package to be installed.

You can list all of your Memcached servers in the config/cache.php config file. This file contains a memcached.servers entry to get you started:

<?
	'memcached' => [
	    'servers' => [
	        [
	            'host' => env('MEMCACHED_HOST', '127.0.0.1'),
	            'port' => env('MEMCACHED_PORT', 11211),
	            'weight' => 100,
	        ],
	    ],
	],
?>

If needed, set the host option to a UNIX socket path. If you do this, the port option should be set to 0:

<?
	'memcached' => [
	    [
	        'host' => '/var/run/memcached/memcached.sock',
	        'port' => 0,
	        'weight' => 100
	    ],
	],
?>


Redis
==========

Before using a Redis cache with Laravel, you need to either install the PhpRedis PHP extension via PECL or install the predis/predis package (~1.0) via Composer.

Laravel Sail already includes this extension.

Official Laravel deployment platforms such as Forge and Vapor have the PhpRedis extension installed by default.



