
Laravel provides a clean, simple email API powered by the popular SwiftMailer library.

Laravel and Swiftmailer provide drivers for sending email via SMTP, Mailgun, Postmark, Amazon SES, and sendmail.


Configuration
================

Laravel's email services can be configured via your app's config/mail.php config file.

Each mailer configured within this file may have its own unique configuration and even its own unique transport, allowing your app to use different email services to send certain email messages.

For example, your app might use Postmark to send transactional emails while using Amazon SES to send bulk emails.

Within your mail config file, there's a mailers configuration array. This array contains a sample config entry for each of the major mail drivers/transports supported by Laravel.

The default configuration value determines which mailer will be used by default when your app needs to send an email message.


Driver/Transport Prerequisites
===============================

The API based drivers such as Mailgun and Postmark are often simpler and faster than sending mail via SMTP servers. It's recommended to use one of those drivers whenever possible.

All of the API based drivers require the Guzzle HTTP library which can be installed via Composer:

	<? composer require guzzlehttp/guzzle ?>


Mailgun Driver
===============

To use Mailgun, first install Guzzle. 

Then, set the default option in your config/mail.php file to mailgun.

Next, verify that config/services.php contains the following options:

<?
	'mailgun' => [
	    'domain' => env('MAILGUN_DOMAIN'),
	    'secret' => env('MAILGUN_SECRET'),
	],
?>

If you're not using the US Mailgun region, you can define your region's endpoint in the services config file:

<?
	'mailgun' => [
	    'domain' => env('MAILGUN_DOMAIN'),
	    'secret' => env('MAILGUN_SECRET'),
	    'endpoint' => env('MAILGUN_ENDPOINT', 'api.eu.mailgun.net'),
	],
?>


Postmark Driver
=================

To use Postmark, install its SwiftMailer transport via Composer:

	<? composer require wildbit/swiftmailer-postmark ?>

Next, install Guzzle and set the default option in your config/mail.php file to postmark.

Then, verify that your config/services.php contains the following options:

<?
	'postmark' => [
	    'token' => env('POSTMARK_TOKEN'),
	],
?>

If you need to specify the Postmark message stream that should be used by a given mailer, add the message_stream_id config option to the mailer's config array.

This config array can be found in your app's config/mail.php file:

<?
	'postmark' => [
	    'transport' => 'postmark',
	    'message_stream_id' => env('POSTMARK_MESSAGE_STREAM_ID'),
	],
?>

Now you can set up multiple Postmark mailers with different message streams.


SES Driver
=================

To use the Amazon SES driver, install the Amazon AWS SDK for PHP via Composer:

	<? composer require aws/aws-sdk-php ?>

Next, set the default option in your config/mail.php config file to ses and verify that your config/services.php contains the following options:

<?
	'ses' => [
	    'key' => env('AWS_ACCESS_KEY_ID'),
	    'secret' => env('AWS_SECRET_ACCESS_KEY'),
	    'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
	],
?>

If you need to define additional options that Laravel should pass to the AWS SDK's SendRawEmail method when sending an email, define an options array within your ses config:

<?
	'ses' => [
	    'key' => env('AWS_ACCESS_KEY_ID'),
	    'secret' => env('AWS_SECRET_ACCESS_KEY'),
	    'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
	    'options' => [
	        'ConfigurationSetName' => 'MyConfigurationSet',
	        'Tags' => [
	            ['Name' => 'foo', 'Value' => 'bar'],
	        ],
	    ],
	],
?>


