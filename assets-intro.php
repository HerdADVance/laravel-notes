
Laravel Mix, a package developed by Jeffrey Way, provides a fluent API for defining webpack build steps for your Laravel app using several common CSS and JS pre-processors.

Mix makes in a cinch to compile and minify your app's CSS and JS files. Through simple chaining, you can fluently define your asset pipeline. For example:

<script>
	mix.js('resources/js/app.js', 'public/js')
    	.postCss('resources/css/app.css', 'public/css');
</script>

You're not required to use Mix. You can use any asset pipeline tool, or none at all.


Installation
=============

Before running Mix, make sure you have Node.js and NPM installed on your machine. You can invoke Node and NPM through Laravel Sail if using that.

To install Mix, simply run <? npm install ?> on the command line. The package.json file that comes with Laravel already includes everything you need to get started.


Running Mix
============

Mix is a configuration layer on top of webpack so to run your Mix tasks, you only need to execute one of the NPM scripts that is included in the default Laravel package.json file.

When you run the dev or production scripts, all of your app's CSS and JS assets will be compiled and placed in your app's public directory:

<?
	// Run all Mix tasks...
	npm run dev

	// Run all Mix tasks and minify output...
	npm run prod

?>

The npm run watch command will continue running in your terminal and watch all relevant CSS and JS files for changes.

Webpack will automatically recompile your assets when it detects a change to one of these files:

	<? npm run watch ?> 

Webpack may not be able to detect your file changes in certain local dev environments. If this is the case, consider using the watch-poll command
	
	<? npm run watch-poll ?>

