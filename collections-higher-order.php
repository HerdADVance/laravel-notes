
Higher Order Messages
======================

Collections provide support for higher order messages, which are shortcuts for performing common actions on collections.

The collection methods that provide higher order messages are:

<? 
	average, avg, contains, each, every, filter, first, flatMap, groupBy, keyBy, map, max, min, partition, reject, skipUntil, skipWhile, some, sortBy, sortByDesc, sum, takeUntil, takeWhile, unique.
?>

Each higher order message can be accessed as a dynamic property on a collection instance.

For example, let's use the "each" higher order message to call a method on each object within a collection:

<?
	use App\Models\User;

	$users = User::where('votes', '>', 500)->get();

	$users->each->markAsVip();
?>

And here we're using the "sum" higher order message to gather the total number of votes for a collection of users:

<?
	$users = User::where('group', 'Development')->get();

	return $users->sum->votes;
?>