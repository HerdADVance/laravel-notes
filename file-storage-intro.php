
Laravel provides a powerful filesystem abstraction thanks to the wonderful Flysystem PHP package by Frank de Jonge.

The Laravel Flysystem integration provides simple drivers for working with local filesystems, SFTP, and Amazon S3.

It's amazingly simple to switch between these storage options between your local dev machine and production server as the API remains the same for each system.


Configuration
==============

Laravel's filesystem config file is located at config/filesystems.php.

Within this file, you can configure all of your system's disks.

Each disk represents a particular storage driver and storage location.

Example configs for each supported driver are included in the config file so you can modify the configuration to reflect your storage preferences and credentials.

The local driver interacts with files stored locally on the server running the Laravel application while s3 driver is used to write to Amazon's S3 cloud storage service.

You can configure as many disks as you like and can even have multiple disks that use the same driver.


The Local Driver
=================

When using the local driver, all file operations are relative to the root directory defined in your filesystems config file.

By default, this is set to the storage/app directory.

Therefore, the following method would write to storage/app/example.txt:

<?
	use Illuminate\Support\Facades\Storage;

	Storage::disk('local')->put('example.txt', 'Contents');
?>


The Public Disk
=================

The public disk included in your app's filesystems config file is intended for files that are going to be publicly accessible. 

By default, the public disk uses the local driver and stores its files in storage/app/public.

To make these files accessible from the web, you should create a symbolic link from public/storage to storage/app/public.

Utilizing this folder convention will keep your publicly accessible files in one directory that can be easily shared across deployments when using zero down-time deployment systems like Envoyer.

To create a symbolic link: <? php artisan storage: link ?>

Once a file has been stored and the symbolic link has been created, you can create a URL to the files using the asset helper method:

	<? echo asset('storage/file.txt'); ?>

You can configure additional symbolic links in your filesystems config file. Each of the configured links will be created when you run the storage:link command:

<?
	'links' => [
    	public_path('storage') => storage_path('app/public'),
    	public_path('images') => storage_path('app/images'),
	],
?>



Driver Prerequisites
========================

Composer Packages
==================

Before using the S3 or SFTP drivers, you'll need to install the appropriate package via the Composer package manager:

<?
	// Amazon S3
	composer require league/flysystem-aws-s3-v3 ~1.0

	// SFTP
	composer require league/flysystem-sftp ~1.0
?>

In addition, you can choose to install a cached adapter for increased performance:

<?
	composer require league/flysystem-cached-adapter ~1.0
?>


S3 Driver Configuration
========================

The S3 driver config info is located in your config/filesystems.php file.

This file contains an example config array for an S3 driver.

You're free to modify this array with your own S3 config and credentials. For convenience, these environment variables match the naming convention used by the AWS CLI.


FTP Driver Configuration
=========================

Laravel's Flysystem integrations work great with FTP, but a sample config isn't included with the framework's default filesystems.php config file.

If you need to configure an FTP filesystem, use the config example below:

<?
	'ftp' => [
	    'driver' => 'ftp',
	    'host' => 'ftp.example.com',
	    'username' => 'your-username',
	    'password' => 'your-password',

	    // Optional FTP Settings...
	    // 'port' => 21,
	    // 'root' => '',
	    // 'passive' => true,
	    // 'ssl' => true,
	    // 'timeout' => 30,
	],
?>


SFTP Driver Configuration
==========================

Laravel's Flyssytem integrations work great with SFTP, but a sample config isn't included with the framework's default filesystem.php config file.

If you need to configure an SFTP filesystem, use the config example below:

<?
	'sftp' => [
	    'driver' => 'sftp',
	    'host' => 'example.com',
	    'username' => 'your-username',
	    'password' => 'your-password',

	    // Settings for SSH key based authentication...
	    'privateKey' => '/path/to/privateKey',
	    'password' => 'encryption-password',

	    // Optional SFTP Settings...
	    // 'port' => 22,
	    // 'root' => '',
	    // 'timeout' => 30,
	],
?>


Caching
===============

To enable caching for a given disk, you can add a cache directive to the disk's config options.

The cache option should be an array of caching options containing the disk name, the expire time in seconds, and the cache prefix:

<?
	's3' => [
	    'driver' => 's3',

	    // Other Disk Options...

	    'cache' => [
	        'store' => 'memcached',
	        'expire' => 600,
	        'prefix' => 'cache-prefix',
	    ],
	],
?>



Obtaining Disk Instances
=================================

The storage facade can be used to interact with any of your configured disks.

For example, you can use the put method on the facade to store an avatar on the default disk.

If you call methods on the Storage facade without first calling the disk method, the method will automatically be passed to the default disk:

<?
	use Illuminate\Support\Facades\Storage;

	Storage::put('avatars/1', $content);
?>

If your app interacts with multiple disks, you can use the disk method on the Storage facade to work with files on a particular disk:

<? 
	Storage::disk('s3')->put('avatars/1', $content);
?>


