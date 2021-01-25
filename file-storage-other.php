
Deleting Files
=================

The delete method accepts a single filename or an array of files to delete:

<?
	use Illuminate\Support\Facades\Storage;

	Storage::delete('file.jpg');

	Storage::delete(['file.jpg', 'file2.jpg']);
?>

If necessary, specify the disk that the file should be deleted from:

<?
	use Illuminate\Support\Facades\Storage;

	Storage::disk('s3')->delete('path/file.jpg');
?>



Directories
==============

Methods to return files and directories:

<?
	use Illuminate\Support\Facades\Storage;

	// gets all files in a directory
	$files = Storage::files($directory);

	// gets all files in directory and files in its subdirectories
	$files = Storage::allFiles($directory);

	// gets all directories within a given directory
	$directories = Storage::directories($directory);

	// gets all directories and its subdirectories 
	$directories = Storage::allDirectories($directory);

	// create a directory
	Storage::makeDirectory($directory);

	// remove a directory
	Storage::deleteDirectory($directory);

?>



Custom Filesystems
====================

Laravel's Flysystem integration provides support for several drivers out of the box but is not limited to these and has adapters for many other storage systems.

You can create a custom driver if you want to use one of these additional adapters in your Laravel app.

In order to define a custom filesystem, you'll need a Flysystem adaper.

This example will add a community maintained Dropbox adapter to our project:

	<? composer require spatie/flysystem-dropbox ?>

Next, register the driver within the boot method of one of your app's service providers. Use the extend method of the Storage facade:

<?
	namespace App\Providers;

	use Illuminate\Support\Facades\Storage;
	use Illuminate\Support\ServiceProvider;
	use League\Flysystem\Filesystem;
	use Spatie\Dropbox\Client as DropboxClient;
	use Spatie\FlysystemDropbox\DropboxAdapter;

	class AppServiceProvider extends ServiceProvider
	{
	    public function register()
	    {
	        //
	    }

	    public function boot()
	    {
	        Storage::extend('dropbox', function ($app, $config) {
	            $client = new DropboxClient(
	                $config['authorization_token']
	            );

	            return new Filesystem(new DropboxAdapter($client));
	        });
	    }
	}
?>

The 1st argument of the extend method is the name of the driver. 

The 2nd is a closure that receives the $app and $config variables.

The closure must return an instance of the League\Flysystem\Filesystem.

The $config variable contains the values defined in config/filesystems.php for the specified disk.

Once you've created and registered the extension's service provider, use the dropbox driver in your config/filesystems.php config file.


