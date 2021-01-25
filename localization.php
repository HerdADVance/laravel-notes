
Laravel's localization features provide a convenient way to retrieve strings in various languages, allowing you to easily support multiple languages within your app.

Laravel provides two ways to manage tranlsation strings:

First, language strings can be stored in files within the resources/lang directory. Within this directory there can be subdirectories for each language supported by your app. This is the approach Laravel uses to manage translation for built-in Laravel features such as validation error messages:

/resources
    /lang
        /en
            messages.php
        /es
            messages.php

The second way is to have translation strings defined with JSON files that are placed within the resoures/lang directory. Each language supported by your app would have its own JSON file within this directory. This approach is recommended for apps that have a large number of translatable strings:

/resources
    /lang
        en.json
        es.json


Configuring the Locale
========================

The default language for your app is stored in the config/app.php file's locale config option.

You can modify the default language for a single HTTP request at runtime using the setLocale method provided by the App facade:

<?
	use Illuminate\Support\Facades\App;

	Route::get('/greeting/{locale}', function ($locale) {
	    if (! in_array($locale, ['en', 'es', 'fr'])) {
	        abort(400);
	    }

	    App::setLocale($locale);

	    //
	});
?>

You can configure a fallback language that will be used when the active language doesn't contain a given translation string. 

Like the default language, the fallback language is also configured in the config/app.php file:

<?
	'fallback_locale' => 'en',
?>


Determining the Current Locale
=================================

You can use the currentLocale and isLocale methods on the App facade to determine the current locale or check if the locale is a given value:

<?
	use Illuminate\Support\Facades\App;

	$locale = App::currentLocale();

	if (App::isLocale('en')) {
	    //
	}
?>



Defining Translation Strings
=============================

All language files return an array of keyed strings:

<?
	// resources/lang/en/messages.php
	return [
	    'welcome' => 'Welcome to our application!',
	];
?>


Using Translation Strings as Keys
==================================

For apps with a large number of translatable strings, defining every string with a short key can become confusing when referencing they keys in your views, and it's also cumbersome to continually invent keys for every translation string supported by your app.

For this reason, Laravel also provides support for defining translation strings using the default translation of the string as the key. These are stored as JSON files:

<script>
	// resources/lang/es.json
	{
	    "I love programming.": "Me encanta programar."
	}
</script>

Don't define translation keys that conflict with other translation filenames. 

For example, translating __('Action') for the "NL" locale with a nl/action.php file exists but a nl.json file doesn't will result in the translator returning the contents of nl/action.php.



Retrieving Translation Strings
===============================

You can retrieve translation strings from your language files using the <? __ ?> helper function.

If you're using short keys to define your translation strings, pass the file that contains the key and the key itself to the <? __ ?> function using dot syntax.

<? 
	// retrieve the welcome translation string from resources/lang/en/messages.php
	echo __('messages.welcome'); 
?>

If the specified translation string doesn't exist, the <? __ ?> function will return the translation string key.

If using default translation strings ans translation keys, pass the default translation of your string to the <? __ ?> function:

<?
	echo __('I love programming.');
?>

If you're using Blade, you can do this:

<?
	{{ __('messages.welcome') }}
?>


Replacing Parameters in Translation Strings
============================================

You can define placeholders in your translation strings. They're prefixed with a colon.

<?
	// defining a welcome message with a placeholder name (notice the colon)
	'welcome' => 'Welcome, :name',

	// replacing placeholders when retrieving a translation string, 2nd arg is array of replacement
	echo __('messages.welcome', ['name' => 'dayle']);

	// capitalize accordingly
	'welcome' => 'Welcome, :NAME', // Welcome, DAYLE
	'goodbye' => 'Goodbye, :Name', // Goodbye, Dayle
?>


Pluralization
================

Laravel can help translate strings differently based on pluralization rules that you define.

Use the pipe character to distinguish between singular and plural forms of a string:

<?
	'apples' => 'There is one apple|There are many apples',

	'apples' => '{0} There are none|[1,19] There are some|[20,*] There are many',
?>

Or in JSON:

<script>
	{
    	"There is one apple|There are many apples": "Hay una manzana|Hay muchas manzanas"
	}
</script>



Overriding Package Language Files
==================================

Some packages ship with their own language files. Instead of changing the package's core files, you can override them by placing files in the resources/lang/vendor/{package}/{locale} directory.

Any translation strings you don't override will still be loaded from the package's original language files.




