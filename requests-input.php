
Various methods for retrieving input data associated with the request:

<?
	// Get all of the request data. Can be used if incoming request is from HTML form or is XHR request
	$input = $request->all();

	// You can access all user input from the Request instance regardless of HTTP verb
	$name = $request->input('name');

	// Same as above with optional 2nd arg that will be made the value of $name is no name exists
	$name = $request->input('name', 'Sally');

	// Getting input from array. Use the dot notation to access them
	$name = $request->input('products.0.name');
	$names = $request->input('products.*.name');

	// Retrieve all input values as an associative array
	$input = $request->input();

?>

The query method only retrieves values from the query string. This is opposed to the input methods above that retrieve values from the entire request payload including the query string.

<?
	$name = $request->query('name');

	// Optional 2nd arg to set to name if no name exists
	$name = $request->query('name', 'Helen');

	// Gets all query string values as associative array
	$query = $request->query();

?>

If the Content-type header of the request is properly set to application/json, you can use the input method to access JSON data. The dot syntax helps retrieve nested values in the JSON:

<?
	$name = $request->input('user.name');
?>

When dealing with HTML elements like checkboxes, your app may receive truthy values that are actually strings. The boolean method will retrieve these as true or false:

<?
	// Will return true for the following:  1, "1", true, "true", "on", "yes" 
	$archived = $request->boolean('archived');
?>

You can access user input using dynamic properties on the Request instance. If one your application's forms contains a name field, you can access it like below.

When using dynamic properties, Laravel first looks at the parameter's value in the request payload. If not present, it searches for the field in the matched route's parameters.

<?
	$name = $request->name;
?>

If you only want a subset of the input data, use the only or except methods:

<?
	// Finds matches. Can be array or list of strings
	$input = $request->only(['username', 'password']);
	$input = $request->only('username', 'password');

	// Excludes matches. Also can be array or list of strings
	$input = $request->except(['credit_card']);
	$input = $request->except('credit_card');
?>


There are plenty of methods to determine if a value is present on the request:

<?
	// Returns true if any value is present
	if ($request->has('name')){}

	// Returns true if ALL specified values are present
	if ($request->has(['name', 'email'])){}

	// Returns true if ANY specified values are present
	if ($request->hasAny(['name', 'email'])){}
	
	// Will execute a closure if a value is present
	$request->whenHas('name', function ($input) {})

	// Will execute a closure if a value is present AND not empty
	$request->whenFilled('name', function ($input) {))

	// Returns true if value present AND not empty
	if ($request->filled('name')){}

	// Returns true if value is absent from request
	if ($request->missing('name')){}
?>


Old Input
============

Laravel allows you to keep input from one request during the next request. This is useful for re-populating forms after detecting validation errors.

If you're using Laravel's included validation features, it's possible you won't need to manually use these session input flashing methods directly, as some of the built-in features will call them automatically.

<?
	// Will flash the current input to the session so it's available during next user request to app
	$request->flash();

	// Flash subset of request data to the session. Useful for keeping passwords and sensitive info out
	$request->flashOnly(['username', 'email']);
	$request->flashExcept('password');
?>

You can chain input flashing onto a redirect since you often want to use that info to redirect to a previous page:

<?
	return redirect('form')->withInput();
	
	return redirect()->route('user.create')->withInput();
	
	return redirect('form')->withInput(
    	$request->except('password')
	);
?>

To retrieve flashed input from the previous request, invoke the old method.

<?
	$username = $request->old('username');
?>

Laravel also provides a global old helper. If you're displaying old input within a Blade template, it's more convenient to use that helper to repopulate the form.

	<input type="text" name="username" value="{{ old('username') }}">


Cookies
==================

All cookies created by Laravel are encrypted and signed with an authentication code, meaning they'll be considered invalid if changed by the client.

To retrieve a cookie value from the request, use the cookie method on a Request instance:

<?
	$value = $request->cookie('name');
?>


Input Trimming & Normalization
==============================

By default, Laravel includes the TrimStrings and ConvertEmptyStringsToNull middleware in your application's global middleware stack. These are listed in the kernel.

These middleware automatically trim all incoming string fields on the request and convert empty strings to null. This allows you to not have to worry about normalization concerns in routes and controllers.

To disable this behavior, remove the middleware from the kernel.




















