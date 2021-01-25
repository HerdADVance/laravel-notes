
The Illuminate\Support\Collection class provides a fluent, convenient wrapper for working with arrays of data.

The Collection Class allows you to chain its methods to perform fluent mapping and reducing of the underlying array.

In general, collections are immutable, meaning every Collection method returns an entirely new Collection instance.

The results of Eloquent queries are always returned as Collection instances.

<?
	// In this example, we're turning an array into a collection, giving the names caps and rejecting the null
	$collection = collect(['taylor', 'abigail', null])->map(function ($name) {
	    return strtoupper($name);
	})->reject(function ($name) {
	    return empty($name);
	});
?>


Creating Collections
=====================

The collect helper returns a new Collection instance for the given array. It's as simple as:

	<? $collection = collect([1, 2, 3]); ?>


Extending Collections
======================

Collections are "macroable," meaning you can add additional methods to the Collection class at runtime.

The Collection class's macro method accepts a closure that will be executed when your macro is called.

That closure may access the collection's other methods via $this as if it were a real method on the collection class.

This example adds a toUpper method to the Collection class:

<?
	use Illuminate\Support\Collection;
	use Illuminate\Support\Str;

	Collection::macro('toUpper', function () {
	    return $this->map(function ($value) {
	        return Str::upper($value);
	    });
	});

	$collection = collect(['first', 'second']);

	$upper = $collection->toUpper();

	// ['FIRST', 'SECOND']
?>

Typically, you should declare collection macros in the boot method of a service provider.

If necessary, you can define macros that accept additional arguments:

<?
	use Illuminate\Support\Collection;
	use Illuminate\Support\Facades\Lang;
	use Illuminate\Support\Str;

	Collection::macro('toLocale', function ($locale) {
	    return $this->map(function ($value) use ($locale) {
	        return Lang::get($value, [], $locale);
	    });
	});

	$collection = collect(['first', 'second']);

	$translated = $collection->toLocale('es');
?>


