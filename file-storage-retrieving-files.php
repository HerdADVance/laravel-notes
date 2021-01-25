
Retrieving Files
==================

The get method can be used to retrieve the contents of a file.

The raw contents of the file will be returned by the method.

All file paths should be relative to the disk's root location.

<?
	// simple "get" method to retrieve contents of file
	$contents = Storage::get('file.jpg');

	// "exists" method used to determine if a file exists on the disk:
	if (Storage::disk('s3')->exists('file.jpg')) {
	    // ...
	}

	// "missing" method used to determine if a file is missing from the disk:
	if (Storage::disk('s3')->missing('file.jpg')) {
    	// ...
	}
?>


Downloading Files
===================

The download method can be used to generate a response that forces the user's browser to download the file at the given path.

This method accepts a filename as the 2nd argument to the method, which will determine the filename that is seen by the user downloading the file.

Finally, you can pass an array of HTTP headers as the 3rd argument:

<?
	use Illuminate\Support\Facades\Storage;

	return Storage::download('file.jpg');

	// same thing with optional 2nd and 3rd args
	return Storage::download('file.jpg', $name, $headers);
?>


File URLs
===============

You can use the url method to get the URL for a given file.

If you're using the local driver, this will typically just prepend /storage to the given path and return a relative URL to the file.

If you're using the s3 driver, the fully qualified remote URL will be returned:

<?
	use Illuminate\Support\Facades\Storage;

	$url = Storage::url('file.jpg');
?>


Temporary URLs
================

Using the temporaryUrl method, you can create temporary URLs to files stored using the s3 driver.

This method accept a path and a DateTime instance specifying when the URL should expire:

<?
	use Illuminate\Support\Facades\Storage;

	$url = Storage::temporaryUrl(
	    'file.jpg', now()->addMinutes(5)
	);
?>

If you need to specify additional S3 request params, you can pass the array of request params as the 3rd argument to the temporaryURl method:

<?
	$url = Storage::temporaryUrl(
	    'file.jpg',
	    now()->addMinutes(5),
	    [
	        'ResponseContentType' => 'application/octet-stream',
	        'ResponseContentDisposition' => 'attachment; filename=file2.jpg',
	    ]
	);
?>


URL Host Customization
========================

If you want to pre-define the host for URLs generated using the Storage facade, you can add a url option to the disk's config array:

<?
	'public' => [
	    'driver' => 'local',
	    'root' => storage_path('app/public'),
	    'url' => env('APP_URL').'/storage',
	    'visibility' => 'public',
	],
?>


File Metadata
================

In addition to reading and writing files, Laravel can also provide info about the files themselves.

<?
	use Illuminate\Support\Facades\Storage;

	// size method gets the size of a file in bytes
	$size = Storage::size('file.jpg');

	// lastModified method returns the UNIX timestampe of the last time the file was modified
	$time = Storage::lastModified('file.jpg');

	// path method get the absolute path to the file for local driver or relative path to file in S3 bucket
	$path = Storage::path('file.jpg');
?>







