
The put method can be used to store file contents on a disk.

You can also pass a PHP resource to the put method which will use Flysystem's underlying stream support.

All file paths should be specified relative to the root location configured for the disk:

<?
	use Illuminate\Support\Facades\Storage;

	Storage::put('file.jpg', $contents);

	Storage::put('file.jpg', $resource);
?>


Automatic Streaming
====================

Streaming files to storage offers significantly reduced memory usage.

If you want Laravel to automatically manage streaming a given file to your storage location, use the putFile or putFileAs alias method.

This method accepts either an <? Illuminate\Http\File ?> or <? Illuminate\Http\UploadedFile ?> instance and will automatically stream the file to your desired location:

<?
	use Illuminate\Http\File;
	use Illuminate\Support\Facades\Storage;

	// Automatically generate a unique ID for filename...
	$path = Storage::putFile('photos', new File('/path/to/photo'));

	// Manually specify a filename...
	$path = Storage::putFileAs('photos', new File('/path/to/photo'), 'photo.jpg');
?>

Note that we only specified a directory name and not a filename. By default, the putFile method will generate a unique ID to serve as the filename.

The file's extension will be determined by examining the file's MIME type.

The path to the file will be returned by the putFile method so you can store the path, including the generated filename, in your DB.

The putFile and putFileAs methods also accept an argument to specify the "visibility" of the stored file.

This is useful is you're storing the file on a cloud disk such as Amazon S3 and would like the file to be publicly accessible via generated URLs:

	<? Storage::putFile('photos', new File('/path/to/photo'), 'public'); ?>


Prepending & Appending to Files
================================

The prepend and append methods allow you to write to the beginning or end of a file:

<?
	Storage::prepend('file.log', 'Prepended Text');

	Storage::append('file.log', 'Appended Text');
?>


Copying & Moving Files
=======================

The copy method can be used to copy an existing file to a new location on the disk. The move method will rename or move an existing file to a new location:

<?
	Storage::copy('old/file.jpg', 'new/file.jpg');

	Storage::move('old/file.jpg', 'new/file.jpg');
?>



File Uploads
==========================

In web apps, one of the most common use-cases for storing files is storing user uploaded files such as photos as documents.

Laravel makes it easy to store uploaded files using the store method on an uploaded file instance.

Call the store method with the path at which you wish to store the uploaded file:

<?
	namespace App\Http\Controllers;

	use App\Http\Controllers\Controller;
	use Illuminate\Http\Request;

	class UserAvatarController extends Controller
	{
	    /**
	     * Update the avatar for the user.
	     *
	     * @param  \Illuminate\Http\Request  $request
	     * @return \Illuminate\Http\Response
	     */
	    public function update(Request $request)
	    {
	        $path = $request->file('avatar')->store('avatars');

	        return $path;
	    }
	}
?>

Note that we only specified a directory name and not a filename. By default, the store method will generate a unique ID to serve as the filename.

The file's extension will be determined by examining the file's MIME type.

The path to the file will be returned by the store method so you can store the path, including the generated filename, in your DB.

You can also call the putFile method of the Storage facade to perform the same file storage operation as in the example above:

<?
	$path = Storage::putFile('avatars', $request->file('avatar'));
?>


Specifying a File Name
========================

If you don't want a filename to be automatically assigned to the stored file, use the storeAs method. This method receives the path, filename, and (optional) disk as its arguments:

<?
	$path = $request->file('avatar')->storeAs(
    	'avatars', $request->user()->id
	);
?>

You can also use the putFileAs method of the Storage facade to perform the same file storage operation as the example above:

<?
	$path = Storage::putFileAs(
	    'avatars', $request->file('avatar'), $request->user()->id
	);
?>


Specifying a Disk
====================

By default, the uploaded file's store method will use your default disk.

To specify another dish, pass the disk name as the 2nd argument to the store method:

<?
	$path = $request->file('avatar')->store(
    	'avatars/'.$request->user()->id, 's3'
	);
?>

If using the storeAs method, pass the disk name as the 3rd argument:

<?
	$path = $request->file('avatar')->storeAs(
    	'avatars',
    	$request->user()->id,
    	's3'
	);
?>


Other Uploaded File Information
=================================

If you want to get the original name of the uploaded file, use the getClientOriginalName method:

<?
	$name = $request->file('avatar')->getClientOriginalName();

	// use the extension method for the file extension of the uploaded file
	$extension = $request->file('avatar')->extension();
?>


File Visibility
=====================

In Laravel's Flysystem integration, visibility is an abstraction of file permissions across multiple platforms.

Files can either be declared public or private.

When public, files are accessible to other. When using the S3 driver, you may retrieve URLs for public files.

You can set the visibility when writing the file via the put method:

<?
	use Illuminate\Support\Facades\Storage;

	Storage::put('file.jpg', $contents, 'public');
?>

If the file has already been stored, its visibility can be retrieved and set via the getVisibility and setVisibility methods:

<?
	$visibility = Storage::getVisibility('file.jpg');

	Storage::setVisibility('file.jpg', 'public');
?>

When interacting with uploaded files, you can use the storePublicly and storePubliclyAs methods to store the uploaded file with public visibility:

<?
	$path = $request->file('avatar')->storePublicly('avatars', 's3');

	$path = $request->file('avatar')->storePubliclyAs(
	    'avatars',
	    $request->user()->id,
	    's3'
	);
?>

When using the local driver, public visibility translates to 0755 permissions for directories and 0644 permissions for files.

You can modify the persmissions mappings in your application's filesystems config file:

<?
	'local' => [
	    'driver' => 'local',
	    'root' => storage_path('app'),
	    'permissions' => [
	        'file' => [
	            'public' => 0664,
	            'private' => 0600,
	        ],
	        'dir' => [
	            'public' => 0775,
	            'private' => 0700,
	        ],
	    ],
	],
?>

