
Views provide a convenient way to place all of our HTML in separate files. They separate your controller/application logic from your presentation logic.

They're stored in the resouces/views directory. A file named greeting.blade.php in that folder would be returned using the global view helper:

<?
	Route::get('/', function () {
    	return view('greeting', ['name' => 'James']);
	});
?>

Views can also be returned using the View facade:

<?
	use Illuminate\Support\Facades\View;

	return View::make('greeting', ['name' => 'James']);
?>

Views can also be nested with subdirectories of resources/views. Use the dot notation to reference them:

<?
	// resources/views/admin/profile.blade.php
	return view('admin.profile', $data);
?>

You can use the View facade's exists method to determine if a view exists:

<?
	use Illuminate\Support\Facades\View;

	if (View::exists('emails.customer')) {}
?>

Passing Data to Views
=====================

When passing data to views, the data should be an array with key/value pairs.

The with method is an alternative way to add individual pieces of data to the view. It returns an instance of the view object so you can continue chaining methods before returning the view:

<?
	return view('greeting')
	    ->with('name', 'Victoria')
	    ->with('occupation', 'Astronaut');
?>


Sharing Data with All Views
===========================

To do this, use the View facade's share method. Typically, you should place calls to the share method within a service provider's boot method:

<?
	namespace App\Providers;

	use Illuminate\Support\Facades\View;

	class AppServiceProvider extends ServiceProvider
	{
	    public function register()
	    {
	        //
	    }
	    public function boot()
	    {
	        View::share('key', 'value');
	    }
	}
?>


View Composers
=================

View composers are callbacks or class methods that are called when a view is rendered. If you have data you want to be bound with a view each time that view is rendered, a view coposer can help you organize that logic into a single location.

They're particularly useful if the same view is returned by multiple routes or controllers within your application and always need a particular piece of data.

Register a view composer within one of your application's service providers. The following example assumes a new App\Providers\ViewServiceProvider for this logic.

The View facade's composer method will register the view composer. Laravel doesn't include a default directory for class based view composers so organize them however you wish.

As with other service providers, you'll need to add your new one to the providers array in config/app.php.

<?
namespace App\Providers;

	use App\Http\View\Composers\ProfileComposer;
	use Illuminate\Support\Facades\View;
	use Illuminate\Support\ServiceProvider;

	class ViewServiceProvider extends ServiceProvider
	{
	    public function register()
	    {
	        //
	    }

	    public function boot()
	    {
	        // Using class based composers...
	        View::composer('profile', ProfileComposer::class);

	        // Using closure based composers...
	        View::composer('dashboard', function ($view) {
	            //
	        });
	    }
	}
?>

Now that we registered the composer, the compose method of the App\Http\View\Composers\ProfileComposer class will be executed each time the profile view is being rendered. Here's an example of the composer class:

<?
	namespace App\Http\View\Composers;

	use App\Repositories\UserRepository;
	use Illuminate\View\View;

	class ProfileComposer
	{
	    
	    protected $users;

	    public function __construct(UserRepository $users)
	    {
	        // Dependencies automatically resolved by service container...
	        $this->users = $users;
	    }
	    public function compose(View $view)
	    {
	        $view->with('count', $this->users->count());
	    }
	}
?>

To attach a view composer to multiple views at once, pass an array of views as the 1st argument to the composer method:

<?
	use App\Http\Views\Composers\MultiComposer;

	View::composer(
	    ['profile', 'dashboard'],
	    MultiComposer::class
	);
?>


Optimizing Views
================

By default, Blade template views are compiled on demand. When a request requiring a view is executed, Laravel will detemine if a compiled version exists. 

If so, Laravel then determines if the uncompiled view has been modified more recently than the compiled view. If

If not, or the uncompiled view has been modified, Laravel will recompile the view.

Compiling views during the request may have a small negative impact on performance so Laravel provides the view:cache Artisan command to precompile all the views utilized by your app.

For increased performance, you might want to run this command as part of your deployment process:

	php artisan view:cache

The following will clear the view cache:

	php artisan view:clear

