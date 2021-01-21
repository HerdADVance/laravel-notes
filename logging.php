
Laravel provides robust logging services that allow you to log messages to files, the system error log, and even to Slack to notify your entire team.

Laravel logging is based on channels. Each channel represents a specific way of writing log information.

For example, the single channel writes log files to a single log file. The slack channel sends log messages to Slack.

Log messages can be written to multiple channels based on their severity.

Under the hood, Laravel utilizes the Monolog library. It provides support for a variety of powerful log handlers. Laravel makes it easy to configure these handlers and to mix and match them as needed.


Configuration
===============

All of the config options for your app's logging behavior is housed in the config/logging.php file.

This file allows to to configure your app's log channels.

By default, Laravel will use the stack channel when logging messages. This channel is used to aggregate multiple log channels into a single channel. 

Monolog is instantiated with a channel name that matches the current environment, such as production or local. To change this value, add a name option to your channel's config:

<?
	'stack' => [
	    'driver' => 'stack',
	    'name' => 'channel-name',
	    'channels' => ['single', 'slack'],
	],
?>


Available Channel Drivers
==========================

Each log channel is powered by a driver which determines how and where the log message is actually recorded. The following log channel drivers are available in every Laravel app.

An entry for each one is already present in your app's config/logging.php file.

custom
daily
errorlog
monolog
null
papertrail
single
slack
stack
syslog

Learn more about these in the docs if needed.


Building Log Stacks
=====================

The stack driver allows you to combine multiple channels into a single log channel for convenience. Here's an example configuration:

<?
	'channels' => [
	    'stack' => [
	        'driver' => 'stack',
	        'channels' => ['syslog', 'slack'],
	    ],

	    'syslog' => [
	        'driver' => 'syslog',
	        'level' => 'debug',
	    ],

	    'slack' => [
	        'driver' => 'slack',
	        'url' => env('LOG_SLACK_WEBHOOK_URL'),
	        'username' => 'Laravel Log',
	        'emoji' => ':boom:',
	        'level' => 'critical',
	    ],
	],
?>

In the configuration above, our stack channel aggregates two other channels via its channel option, syslog and slack.

When logging messages, both of those channels will have the opportunity to log the message. However, whether those channels actually log the message may be determined by the messages severity or level.


Log Levels
==============

In the example above, the level configuration option present on the syslog and slack channel configs determine the minimum level a message must be in order to be logged by the channel.

Monolog offers all of the log levels defined in the RFC 5424 specification (listed below)

So if we log a message with Debug: 
	
	<? Log::debug('An informational message.'); ?>

The syslog channel will write the message to the system log. But the slack channel will not because it requires a level at Critical or above.


Writing Log Messages
======================

You can write info to the logs using the Log facade.

<?
	use Illuminate\Support\Facades\Log;

	// in order from most to least severe
	Log::emergency($message);
	Log::alert($message);
	Log::critical($message);
	Log::error($message);
	Log::warning($message);
	Log::notice($message);
	Log::info($message);
	Log::debug($message);
?>

You can call any of these to log a message for a corresponding level. By default, the message will be written to the default log channel as configured by your config/logging.php file:

<?
	namespace App\Http\Controllers;

	use App\Http\Controllers\Controller;
	use App\Models\User;
	use Illuminate\Support\Facades\Log;

	class UserController extends Controller
	{
	    public function show($id)
	    {
	        Log::info('Showing the user profile for user: '.$id);

	        return view('user.profile', [
	            'user' => User::findOrFail($id)
	        ]);
	    }
	}

?>

An array of contextual data may be passed to the log methods. This data will be formatted and siplayed with the log message:

	<? Log::info('User failed to login.', ['id' => $user->id]); ?>


Writing to Specific Channels
============================

To log a message to a channel other than your app's default channel, use the channel method on the Log facade to retrieve and log to any channel defined in your config file:

<?
	use Illuminate\Support\Facades\Log;

	Log::channel('slack')->info('Something happened!');
?>

To create an on-demand logging stack consisting of multiple channels, use the stack method:

<?
	Log::stack(['single', 'slack'])->info('Something happened!');
?>


Customizing Monolog for Channels
=================================

If you need complete control over how Monolog is configured for an existing channel, define a tap array on the channel's configuration.

The tap array should contain a list of classes that should have an opportunity to customize (or tap into) the Monolog instance after it's created.

There's no conventional location where these classes should be places so you're free to create a directory within your app to contain them.

<?
	'single' => [
    	'driver' => 'single',
    	'tap' => [App\Logging\CustomizeFormatter::class],
    	'path' => storage_path('logs/laravel.log'),
    	'level' => 'debug',
	],
?>

Once you've configured the tap option on your channel, you can define the class that will customize your Monolog instance. This class only needs an __invoke method which receives an Illuminate\Log\Logger instance. It proxies all method calls to the underlying Monolog instance:

<?
	namespace App\Logging;

	use Monolog\Formatter\LineFormatter;

	class CustomizeFormatter
	{
	    public function __invoke($logger)
	    {
	        foreach ($logger->getHandlers() as $handler) {
	            $handler->setFormatter(new LineFormatter(
	                '[%datetime%] %channel%.%level_name%: %message% %context% %extra%'
	            ));
	        }
	    }
	}
?>


Creating Custom Channels Via Factories
======================================

If you want an entirely custom channel in which you have full control of Monolog's instantiation and configuration, specify a custom driver type in your logging.php config file.

Your config should include a via option that contains the name of the factory class which will be invoked to create the Monolog instance:

<?
	'channels' => [
	    'example-custom-channel' => [
	        'driver' => 'custom',
	        'via' => App\Logging\CreateCustomLogger::class,
	    ],
	],
?>

Once you've configured the custom driver channel, you're ready to define the class that will create your Monolog instance. The method will receive the channels configuration array as its only argument:

<?
	namespace App\Logging;

	use Monolog\Logger;

	class CreateCustomLogger
	{
	    public function __invoke(array $config)
	    {
	        return new Logger(...);
	    }
	}
?>

