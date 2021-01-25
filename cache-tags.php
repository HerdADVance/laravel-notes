
Cache tags are not supported when using the file, dyanmoDB, or database cache drivers.

When using multiple tags with caches that are stored "forever," performance will be best with a driver such as memcached which automatically purges stale records.


Storing Tagged Cache Items
===========================

Cache tags allow you to tag related items in the cache and then flush all cached values that have been assigned a given tag.

You can access a tagged cache by passing in an ordered array of tag names.

For example, let's access a tagged cache and put a value into the cache:

<?
	Cache::tags(['people', 'artists'])->put('John', $john, $seconds);

	Cache::tags(['people', 'authors'])->put('Anne', $anne, $seconds);
?>


Accessing Tagged Cache Items
=============================

To retrieve a tagged cache item, pass the same ordered list of tags to the tags method and then call the get method with the key you wish to retrieve:

<?
	$john = Cache::tags(['people', 'artists'])->get('John');

	$anne = Cache::tags(['people', 'authors'])->get('Anne');
?>


Removing Tagged Cache Items
============================

You can flush all items that are assigned a tag or list of tags.

For example, this statement would remove all caches tagged with either people, authors, or both. So both Anne and John would be removed from the cache:

<?
	Cache::tags(['people', 'authors'])->flush();
?>

By contrast, this statement would remove only cached values with authors, so Anne would be removed, but not John:

<?
	Cache::tags('authors')->flush();
?>


