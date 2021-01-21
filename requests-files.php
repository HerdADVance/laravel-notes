
Retrieving Uploaded Files
=========================

To retrieve uploaded files from a Request instance, use the file method or dynamic properties.

The file method returns an instance of Illuminate's UploadedFile class, which extends the PHP SplFileInfo class and provides a variety of methods for interacting with the file:

<?
	// getting the file with with the file method
	$file = $request->file('photo');

	// getting the file from the dynamic property
	$file = $request->photo;

	// checking to see if the request has a file
	if ($request->hasFile('photo')) {}

	// checking to see if there were no problems uploading the file
	if ($request->file('photo')->isValid()) {}

	// gets the fully-qualified file path and its extension
	$path = $request->photo->path();

	// will attempt to guess the file's extension based on its contents
	$extension = $request->photo->extension();
?>

Check the API documentation for other methods on UploadedFile instances.


Storing Uploaded Files
=======================

To store an uploaded file, you typically use one of your configured filesystems.

The UploadedFile class has a store method that will move the file to one of your disks, which may be a location on your local filesystem of a cloud storage location like Amazon S3.

The store method accepts the path where the file should be stored relative to the filesystem's configured root directory. 

This path should not contain a filename, since a unique ID will be automatically generated to serve as the filename.

The store method also accepts an optional 2nd argument for the name of the disk that should be used to store the file. It returns the path of the file relative to the disk root:

<?
	// stores the file in images
	$path = $request->photo->store('images');

	// stores the file in images on s3
	$path = $request->photo->store('images', 's3');

	// storeAs method if you want your own filename to be used
	$path = $request->photo->storeAs('images', 'filename.jpg');

	// storeAs method with custom filename and location
	$path = $request->photo->storeAs('images', 'filename.jpg', 's3');
?>