
Route-Model binding in Laravel provides a convenient way to automatically inject a model instance directly into your route. 

For example, instead of injecting a user's ID, you can inject the entire User instance that matches that ID.

Implicit Binding
================

Laravel automatically resolves Eloquent models defined in routes or controller actions whose type-hinted variable names match a route segment name:

<?
	use App\Models\User;

	// Will return 404 error automatically if matching instance not found
	Route::get('/users/{user}', function (User $user) {
	    return $user->email;
	});
?>


It's also possible when using controller methods:

<?
	use App\Http\Controllers\UserController;
	use App\Models\User;

	// Route definition...
	Route::get('/users/{user}', [UserController::class, 'show']);

	// Controller method definition...
	public function show(User $user)
	{
	    return view('user.profile', ['user' => $user]);
	}
?>

If you want to resolve an Eloquent model using a column other than ID:

<?
	use App\Models\Post;

	// The returned post variable will be based on a column called slug instead of id
	Route::get('/posts/{post:slug}', function (Post $post) {
	    return $post;
	});
?>

The above can also be done with the getRouteKeyName method on the Eloquent model:

<?
	public function getRouteKeyName()
	{
		// in this case you wouldn't need to specify :slug as in the route definition example above
	    return 'slug'; 
	}
?>


Explicit Binding
================

You're not required to use Laravel's implicit based model resolution for binding. To register an explicit binding, use the router's model method to specify a class for a given parameter.

Define these at the beginning of the boot method of the RouteServiceProvider class:

<?
	public function boot()
	{
	    Route::model('user', User::class);

	}
?>

The Route::bind method allows you to define your own model binding resolution logic. also in the boot method of the RouteServiceProvider class:

<?
	Route::bind('user', function ($value) {
        return User::where('name', $value)->firstOrFail();
    });
?>






