
Resource Controllers
====================

You can quickly create CRUD routes for a controller with the following command:

<? php artisan make:controller PhotoController --resource ?>

This generates a controller in app/Http/Controllers. It will contain a method for each of the available resource operations.

You can then register a resource route that points to that controller. More convenient than a route for each operation:

<?
	use App\Http\Controllers\PhotoController;

	Route::resource('photos', PhotoController::class);
?>

You can also register many resource controllers at once:

<?
	use App\Http\Controllers\PhotoController;
	use App\Http\Controllers\PostController;

	Route::resources([
	    'photos' => PhotoController::class,
	    'posts' => PostController::class,
	]);
?>

Resource Controller Actions
============================

The following 7 actions are handled by a resource controller:

GET    /photos                index
GET    /photos/create         create
POST   /photos                store
GET    /photos/{photo}        show
GET    /photos/{photo}/edit   edit
PUT    /photos/{photo}        update
DELETE /photos/{photo}        destroy


Overwriting Resource Controller Default Actions or Parameters
=============================================================

If you want to use a different name than the default for an action, override it with a names array:

<?
	Route::resource('photos', PhotoController::class)->names([
	    'create' => 'photos.build'
	]);
?>

Similarly, if you want overwrite the default parameters (which are based on the singularized version of the resource name), do so with the parameters method:

<?
	Route::resource('users', AdminUserController::class)->parameters([
	    'users' => 'admin_user'
	]);
?>


Scoping Resource Routes
=======================

Laravel's scoped implicit model binding feature can automatically scope nested bindings so the resolved child model is confirmed to belong to the parent model.

By using the scoped method when defining a nested resource, you can enable automatic scoping and instruct Laravel which field the child resource should be retrieved by:

<?
	// This route registers a scoped nested resource with this URI:
	// /photos/{photo}/comments/{comment:slug}
	Route::resource('photos.comments', PhotoCommentController::class)->scoped([
	    'comment' => 'slug',
	]);
?>

When using a custom keyed implicit binding as a nested route parameter, like above, Laravel will automatically scope the query to retrieve the nested model by its parents using conventions to guess the relationship name on the parent.

In this case, it is assumed that the Photo model has a relationship named comments (plural of route parameter name) which can be used to retrieve the Comment model.


Localizing Resource URI's
=========================

By default, Route::Resource will create resource URI's w/ English verbs. If you need to localize the create and edit action verbs, you can use Route::resourceVerbs.

This can be done at the beginning of the boot method within the RouteServiceProvider:

<?
	public function boot()
	{
	    Route::resourceVerbs([
	        'create' => 'crear',
	        'edit' => 'editar',
	    ]);

	    // ...
	}
?>


Supplementing Resource Controllers
==================================

If you need additional routes beyond the basic 7, they need to be defined before the call to the Route::Resource method. Otherwise, the routes defined by the resource method may unintentionally take precedence.

<?
	use App\Http\Controller\PhotoController;

	Route::get('/photos/popular', [PhotoController::class, 'popular']);
	Route::resource('photos', PhotoController::class);
?>




