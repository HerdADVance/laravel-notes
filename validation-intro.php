
Laravel provides several different approaches to validate your application's incoming data. 

It's most common to use the validate method available on all incoming HTTP requests.

When using the validate method, if your validation rules pass, your code will keep executing normally. However, if it fails, an exception will be thrown and the proper error response will be automatically sent back to the user.

If validation fails during a traditional HTTP request, a redirect response to the previous URL will be generated. If the incoming request is XHR (for XML), a JSON response containing the validation error message will be returned.

In the example below, we're validating the incoming title and body fields in the store method of our Posts controller:

<?
public function store(Request $request)
	{
	    $validated = $request->validate([
	        'title' => 'required|unique:posts|max:255',
	        'body' => 'required',
	    ]);

	    // The blog post is valid...
	}
?>

Each rule is separated by pipes above but can also be an array of strings.

The validateWithBag method can be used to validate a request and store any error messages within a named error bag:

<?
	$validatedData = $request->validateWithBag('post', [
	    'title' => ['required', 'unique:posts', 'max:255'],
	    'body' => ['required'],
	]);
?>

Sometimes you may wish to stop running validation rules on an attribute after the first validation failure. To do that, assign the bail rule to the attribute:

<?
	$request->validate([
	    'title' => 'bail|required|unique:posts|max:255',
	    'body' => 'required',
	]);
?>

If the incoming HTTP request contains nested field data, specify these fields using dot syntax:

<?
	$request->validate([
	    'title' => 'required|unique:posts|max:255',
	    'author.name' => 'required',
	    'author.description' => 'required',
	]);
?>


Displaying Validation Errors
============================

If the incoming request fields don't pass validation rules, Laravel automatically redirects the user back to their previous location. All of the validation errors and request input will be automatically flashed to the session.

An $errors variable is shared with all your application's views by the Illuinate\View\Middleware ShareErrorsFromSession middleware, which is provided by the web middleware group.

When this middleware is applied, an $errors variable will always be available in your views. This variable is an instance of Illuminate\Support\MessageBag.

From our previous example, the user will be directed to the Post controller's create method when validation fails, where we'll display the error messages in the view:
	
	<!-- /resources/views/post/create.blade.php -->
	<h1>Create Post</h1>

	@if ($errors->any())
	    <div class="alert alert-danger">
	        <ul>
	            @foreach ($errors->all() as $error)
	                <li>{{ $error }}</li>
	            @endforeach
	        </ul>
	    </div>
	@endif
	<!-- Create Post Form -->

Laravel's built-in validation rules each have an error message that is located in your app's resouces/lang/en/validation.php file.

Within this file, you'll find a translation entry for each validation rule that you can freely change.

When using the validate method on an XHR request, Laravel will not generate a redirect reponse. Instead, it generates a JSON response containing all of the validation errors with a 422 status code.


The @error Directive
========================

This directive will quickly determine in Blade if validation error messages exist for a given attribute. You can also echo the $message variable to display the error message:

	<label for="title">Post Title</label>

	<input id="title" type="text" class="@error('title') is-invalid @enderror">

	@error('title')
	    <div class="alert alert-danger">{{ $message }}</div>
	@enderror


Repopulating Forms
=========================

When Laravel generates a redirect response due to a validation error, it automatically flashes all of the request's input to the session so you can easily access it during the next request and repopulate the form the user attempted to submit.

To retrieve flashed input from the previous request, invoke the old method on a Request instance.

	<? $title = $request->old('title'); ?>

Laravel also provides a global old helper. If you're displaying old input within a Blade template, it's more convenient to use this helper to repopulate the form.

	<input type="text" name="title" value="{{ old('title') }}">


Optional Fields
=====================

By default, Laravel includes the TrimStrings and ConvertEmptyStringsToNull middleware in your app's global middleware stack. These are listed in the stack by the kernel.

Because of this, you'll often need to mark your optional request fields as nullable if you don't want the validator to consider null values invalid.

<?
	$request->validate([
	    'title' => 'required|unique:posts|max:255',
	    'body' => 'required',
	    'publish_at' => 'nullable|date',
	]);
?>

In that example, the publish_at field may be either null or a valid date. If the nullable modifier is not added to the rule definition, the validator would consider null an invalid date.




