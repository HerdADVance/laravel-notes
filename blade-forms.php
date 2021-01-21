
Anytime you define a HTML form in your app, include the hidden CSRF token in the form so the CSRF protection middleware can validate the request.

	<form method="POST" action="/profile">
	    @csrf

	    ...
	</form>

HTML forms can't make PUT, PATCH, or DELETE requests so you need a hidden _method field to spoof these verbs. Use Blade's @method directive:

	<form action="/foo/bar" method="POST">
	    @method('PUT')

	    ...
	</form>


Use the @error directive to quickly check if validation error messages exist for a given attribute. Within this, you can echo the $message variable to display the error message.

	<label for="title">Post Title</label>

	<input id="title" type="text" class="@error('title') is-invalid @enderror">

	@error('title')
	    <div class="alert alert-danger">{{ $message }}</div>
	@enderror


You can pass the name of a specific error bag as the 2nd parameter to the @error directive to retrieve validation error messages on pages containing multiple forms:

	<label for="email">Email address</label>

	<input id="email" type="email" class="@error('email', 'login') is-invalid @enderror">

	@error('email', 'login')
	    <div class="alert alert-danger">{{ $message }}</div>
	@enderror