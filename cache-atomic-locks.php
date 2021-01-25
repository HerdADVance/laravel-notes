
Atomic Locks
=============

To utilize atomic locks, your application must be using the memcached, redis, dynamodb, database, file, or array cache driver as your app's default cache driver.

All servers must also be communicating with the same central cache server.

When using the database cache driver, you'll need to setup a table to contain your app's cache locks:

<?
	Schema::create('cache_locks', function ($table) {
	    $table->string('key')->primary();
	    $table->string('owner');
	    $table->integer('expiration');
	});
?>

Atomic locks allow for the manipulation of distributed locks without worrying about race conditions.

For example, Laravel Forge uses atomic locks to ensure that only one remote task is being executed on a server at a time.

You can create and manage locks using the Cache::lock method:

<?
	use Illuminate\Support\Facades\Cache;

	$lock = Cache::lock('foo', 10);

	if ($lock->get()) {
	    // Lock acquired for 10 seconds...

	    $lock->release();
	}
?>

The get method also accepts a closure. After the closure is executed, Laravel will automatically release the lock:

<?
	Cache::lock('foo')->get(function () {
    	// Lock acquired indefinitely and automatically released...
	});
?>

If the lock isn't available at the moment you request it, you can instruct Laravel to wait for a specified number of seconds.

If the lock can't be acquired in that time limit, an Illuminate\Contracts\Cache\LockTimeoutException will be thrown:

<?
	use Illuminate\Contracts\Cache\LockTimeoutException;

	$lock = Cache::lock('foo', 10);

	try {
	    $lock->block(5);

	    // Lock acquired after waiting maximum of 5 seconds...
	} catch (LockTimeoutException $e) {
	    // Unable to acquire lock...
	} finally {
	    optional($lock)->release();
	}
?>

The example above may be simplified by passing a closure to the block method.

When a closure is passed to this method, Laravel will attempt to acquire the lock for the specified number of seconds and will automatically release the lock once the closure has been executed:

<?
	Cache::lock('foo', 10)->block(5, function () {
    	// Lock acquired after waiting maximum of 5 seconds...
	});
?>


If you want to acquire a lock in one process and release it in another process, pass the lock's scoped owner token to the queued job so that the job can re-instantiate the lock using the given token.

This is useful if you acquire a lock during a web request and wish to release the lock at the end of a queued job that's triggered by that request.

In the example below, we dispatch a queued job if a lock is successfully acquired. We also pass the lock's owner token to the queued job via the lock's owner method:

<?
	$podcast = Podcast::find($id);

	$lock = Cache::lock('processing', 120);

	if ($result = $lock->get()) {
	    ProcessPodcast::dispatch($podcast, $lock->owner());
	}
?>

Within our app's ProcessPodcast job, we can restore and release the lock using the owner token:

<?
	Cache::restoreLock('processing', $this->owner)->release();

	// Or, release the lock without repsecting its current owner with the forceRelease method
	Cache::lock('processing')->forceRelease();
?>



