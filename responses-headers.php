
Attaching Headers to Responses
==============================

Most response methods are chainable. The header method can be used to add a series of headers to the response before sending it back to the user:

<?
	return response($content)
		->header('Content-Type', $type)
		->header('X-Header-One', 'Header Value')
		->header('X-Header-Two', 'Header Value');

	// or use the withHeaders method to specify an array of headers
	return response($content)
        ->withHeaders([
            'Content-Type' => $type,
            'X-Header-One' => 'Header Value',
            'X-Header-Two' => 'Header Value',
        ]);
?>

Laravel includes a cache.headers middleware that can be used to quickly set the Cache-Control header for a group of routes.

If etag is specified in the the list of directives, an MD5 hash of the response content will automatically be set as the ETag identifier:

<?
	Route::middleware('cache.headers:public;max_age=2628000;etag')->group(function () {
	    Route::get('/privacy', function () {
	        // ...
	    });

	    Route::get('/terms', function () {
	        // ...
	    });
	});
?>
