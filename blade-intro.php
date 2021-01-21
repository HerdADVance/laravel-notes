
All Blade templates are compiled into plain PHP code and cached until they are modified. This means Blade adds essentialy zero overhead to your application.

You can echo the results of any PHP function inside the double brackets: {{ time() }}

Blade's echo statements are automatically sent through PHP's htmlspecialchars function to prevent XSS attacks.

Blade views may be returned from routes or controllers using the global view helper. Data can be passed to the view using the helper's 2nd argument:

<?
	Route::get('/', function () {
	    return view('greeting', ['name' => 'Finn']);
	});

?>

In the template, to access the name variable passed as data above:  Hello, {{ $name }}


Rendering JSON
===============

Sometimes you may pass an array to your view with the intention of rendering it as JSON in order to initialize a JS variable. 

Instead of manually calling json_encode, you can use the @json blade directive.

<script>
    var app = @json($array);

    // or with other optional arguments as in json_encode function
    var app = @json($array, JSON_PRETTY_PRINT);
</script>


HTML Entities
==============

By default, Blade (and the Laravel e helper) will double encode HTML entities. If you want to disable double encoding, call the Blade::withoutDoubleEncoding method from the boot method of your AppServiceProvider:

<?
	namespace App\Providers;

	use Illuminate\Support\Facades\Blade;
	use Illuminate\Support\ServiceProvider;

	class AppServiceProvider extends ServiceProvider
	{
	    public function boot()
	    {
	        Blade::withoutDoubleEncoding();
	    }
	}
?>

If you don't want Blade to send statements through PHP's htmlspecialchars function, use exclamation points: Hello, {!! $name !!}


Blade & JS Frameworks
======================

Since many JS frameworks also use curly braces to indicate a given expression should be displayed in the browser, you can use the @symbol to inform the rendering engine and expression should remain untouched:

	Hello, @{{ name }}

In the above example, the {{ name }} expression will be untouched, allowing it to be rendered by your JS framework.

You can also use the verbatim directive to wrap large portions of the HTML so you don't have to prefix every echo statement with the @ symbol:

	@verbatim
	    <div class="container">
	        Hello, {{ name }}.
	    </div>
	@endverbatim
