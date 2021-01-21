
Sessions
========

HTTP driven applications (like Laravel) are stateless. Sessions provide a way to store information about the user across multiple requests.

Config/session.php is where you can change how sessions will be stored. There are the following options:

- file: default, works well for most applications, stored ini storage/framework/sessions
- cookie: sessions are stored in secure, encrypted cookies
- database: sessions stored in relational DB
- memcached/redis: sessions stored in one of these fast, cache-based stores
- array: sessions stored in a PHP array and not persisted


For database option, you need to create a table to contain the records. The php artisan session:table command will do this.

The Redis option will require the PhpRedis PHP extension or predis/predis package through Composer.


Retrieving Session Data
=======================

Two primary methods of working with session data:

- Request instance
- Global Session Helper

<?
	// Getting session data through Request instance, this is in controller
	public function show(Request $request, $id){
	    $value = $request->session()->get('key'); // 'key' would be whatever session variable you want
	}

	// Getting session data through global session helper
	$value = session('key');
?>

There is little difference between either of the above methods so it seems to be a matter of preference.

Other examples:

<?
	// Request all data
	$data = $request->session()->all();

	// Returns true if users is present and value is not null ('exists' instead of 'has' true if null)
	if ($request->session()->has('users')) { /* do something */ } 
?>


Storing Session Data
====================

Storing data is similar to retreiving:

<?
	// Storing session variable through Request instance
	$request->session()->put('key', 'value');

	// Storing session variable through global session helper
	session(['key' => 'value']);

	// Push to array if key is an array
	$request->session()->push('user.teams', 'developers');
?>


Deleting Data
==============

<?
	// Delete a single key
	$request->session()->forget('name');

	// Delete multiple keys
	$request->session()->forget(['name', 'status']);

	// Delete all keys
	$request->session()->flush();
?>


Flash Data
=============

Flash data is stored in the session for the next request. This is generally used for flash messages:

<? $request->session()->flash('status', 'Task was successful!'); ?>


Regenerating Session ID
=======================

This is done in order to prevent a session fixation attack. Laravel automatically handles this through application starter kits or Laravel Fortify. If you need to do it manually:

<? $request->session()->regenerate(); ?>


Session Blocking
================

Session blocking forces sync on requests for the purpose of. Async by default.

Async is usually not a problem, but session data loss can occur in a small subset of applications that make concurrent requests to two different application endpoints which both write data to the session.

Must use cache drivers that support atommic locks: memcached, dyanmodb, redis, database (not cookie)

<?
	Route::post('/profile', function () {})->block($lockSeconds = 10, $waitSeconds = 10)
?>

The above method would have the session lock be held for a max of 10 seconds (1st arg), and have the request wait 10 seconds (2nd arg) while attempting to obtain a lock.
