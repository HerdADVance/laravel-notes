
Route groups allow you to share route attributes, such as middleware, across a large number of route without having to define those attributes on each one.

Middleware are executed in the order they're listed in the array below:

<?
	Route::middleware(['first', 'second'])->group(function () {
	    Route::get('/', function () {
	        // Uses first & second middleware...
	    });

	    Route::get('/user/profile', function () {
	        // Uses first & second middleware...
	    });
	});
?>

Subdomains
=============

Groups can also be used for subdomain routing by using the domain method. Register subdomain routes before root domain routes to ensure the subdomain routes are reachable and prevent overwriting those with the same URI path.

<?
	Route::domain('{account}.example.com')->group(function () {
	    Route::get('user/{id}', function ($account, $id) {
	        //
	    });
	});
?>


Prefixes
==============

If a bunch of routes have the same parent segment, you can group them like this with the prefix method:

<?
	Route::prefix('admin')->group(function () {
	    Route::get('/users', function () {
	        // Matches The "/admin/users" URL
	    });
	});
?>


And the same concept for route names that start with the same parent. Uses the name method:

<?
	Route::name('admin.')->group(function () {
	    Route::get('/users', function () {
	        // Route assigned name "admin.users"...
	    })->name('users');
	});
?>
