
Lazy Collections
==================

To supplement the Collection class, the LazyCollection class leverages PHP's generators to allow you to work with very large datasets while keeping memory usage low.

For example, if you needed to process a multi-GB log file while taking advantage of Laravel's collection methods to parse the logs, you could use lazy collections to keep only a small part of the file in memory at a given time:

<?
	use App\Models\LogEntry;
	use Illuminate\Support\LazyCollection;

	LazyCollection::make(function () {
	    $handle = fopen('log.txt', 'r');

	    while (($line = fgets($handle)) !== false) {
	        yield $line;
	    }
	})->chunk(4)->map(function ($lines) {
	    return LogEntry::fromLines($lines);
	})->each(function (LogEntry $logEntry) {
	    // Process the log entry...
	});
?>

Or, for example, you needed to iterate through 10,000 Eloquent models. When using traditional Laravel collections, all of those would have to be loaded into memory at the same time:

<?
	use App\Models\User;

	$users = User::all()->filter(function ($user) {
	    return $user->id > 500;
	});
?>

However, the query builder's cursor method returns LazyCollection instance. This allows you to only run a single query against the DB but also keep one Eloquent model loaded in memory at a time.

In this example, the filter callback isn't executed until we actually iterate over each user individually, allowing for a drastic reduction in memory usage:

<?
	use App\Models\User;

	$users = User::cursor()->filter(function ($user) {
	    return $user->id > 500;
	});

	foreach ($users as $user) {
	    echo $user->id;
	}
?>

To create a lazy collection instance, pass a PHP generator function to the collection's make method:

<?
	use Illuminate\Support\LazyCollection;

	LazyCollection::make(function () {
	    $handle = fopen('log.txt', 'r');

	    while (($line = fgets($handle)) !== false) {
	        yield $line;
	    }
	});
?>

Almost all methods available on the Collection class are also available on the LazyCollection class.

Both of these classes implement the Illuminate\Support\Enumerable contract, which defines a whole bunch of methods (check docs if need to know more).

Methods that mutate the collection such as <? shift, pop, prepend ?> etc are NOT available on the LazyCollection class.

In addition to the methods defined by the aforementioned Enumerable contract, the LazyCollection class contains the following methods:


takeUntilTimeout()
==================

The takeUntilTimeout method returns a new lazy collection that will enumerate values until the specified time. After that time, the collection will stop enumerating:

<?
	$lazyCollection = LazyCollection::times(INF)
	    ->takeUntilTimeout(now()->addMinute());

	$lazyCollection->each(function ($number) {
	    dump($number);

	    sleep(1);
	});

	// 1
	// 2
	// ...
	// 58
	// 59
?>

To illustrate the usage of this method, imagine an app that submits invoices from the DB using a cursor. You could define a scheduled task that runs every 15 minutes and only processes invoices for a maximum of 14 minutes:

<?
	use App\Models\Invoice;
	use Illuminate\Support\Carbon;

	Invoice::pending()->cursor()
	    ->takeUntilTimeout(
	        Carbon::createFromTimestamp(LARAVEL_START)->add(14, 'minutes')
	    )
	    ->each(fn ($invoice) => $invoice->submit());
?>


tapEach()
==================

While the each method called the given callback for each item in the collection right away, the tapEach method only calls the given callback as the items are being pulled out of the list one-by-one:

<?
	// Nothing has been dumped so far...
	$lazyCollection = LazyCollection::times(INF)->tapEach(function ($value) {
	    dump($value);
	});

	// Three items are dumped...
	$array = $lazyCollection->take(3)->all();

	// 1
	// 2
	// 3
?>


remember()
================

The remember method returns a new lazy collection that will remember any values that have already been enumerated and will not retrieve them again on subsequent collection enumerations:

<?
	// No query has been executed yet...
	$users = User::cursor()->remember();

	// The query is executed...
	// The first 5 users are hydrated from the database...
	$users->take(5)->all();

	// First 5 users come from the collection's cache...
	// The rest are hydrated from the database...
	$users->take(20)->all();
?>


