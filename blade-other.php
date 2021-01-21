
Stacks
=============

Blade allows you to push to named stacks which can be rendered somewhere else in another view or layout. This is useful for specifying any JS libraries required by your child views.

	@push('scripts')
	    <script src="/example.js"></script>
	@endpush

You can push to a stack as many times as needed. To render the complete stack contents, pass the name of the stack to the @stack directive:

	<head>
    <!-- Head Contents -->

	    @stack('scripts')
	</head>

Use the @prepend directive to prepend to the beginning of a stack:
	
	@push('scripts')
	    This will be second...
	@endpush

	// Later...

	@prepend('scripts')
	    This will be first...
	@endprepend


Service Injection
===================

The @inject directive can be used to retrieve a service from the Laravel service container. 

The 1st argument passed to @inject is the name of the variable the service will be placed into. The 2nd argument is the class or interface name of the service you wish to resolve:

	@inject('metrics', 'App\Services\MetricsService')

	<div>
	    Monthly Revenue: {{ $metrics->monthlyRevenue() }}.
	</div>


Extending Blade
================

Blade allows you to define your own custom directives with the directive method. 

When the Blade compiler encounters the custom directive, it will call the provided callback with the expression the directive contains.

The following example creates a @datetime($var) directive which formats a given $var, which would be an instance of DateTime:

<?
	namespace App\Providers;

	use Illuminate\Support\Facades\Blade;
	use Illuminate\Support\ServiceProvider;

	class AppServiceProvider extends ServiceProvider
	{
	    public function register()
	    {
	        //
	    }

	    public function boot()
	    {
	        Blade::directive('datetime', function ($expression) {
	            return "<?php echo ($expression)->format('m/d/Y H:i'); ?>";
	        });
	    }
	}
?>

After updating the logic of a Blade directive, you'll need to delete all of the cached Blade views using the php artisan view:clear command.


Custom If Statements
====================

Programming a custom directive is sometimes more complex than necessary when defining simple, custom conditional statements. 

For that reason, Blade provides the Blade::if method which allows you to quickly define custom conditional directives using closures.

For example, let's define a custom conditional that checks the configured default "disk" for the application. We do this in the boot method of the AppServiceProvider:

<?
	use Illuminate\Support\Facades\Blade;

	public function boot()
	{
	    Blade::if('disk', function ($value) {
	        return config('filesystems.default') === $value;
	    });
	}
?>

Once the custom conditional has been defined, you can use it within your templates:

	@disk('local')
	    <!-- The application is using the local disk... -->
	@elsedisk('s3')
	    <!-- The application is using the s3 disk... -->
	@else
	    <!-- The application is using some other disk... -->
	@enddisk


	@unlessdisk('local')
	    <!-- The application is not using the local disk... -->
	@enddisk

