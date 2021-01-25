
Browsersync Reloading
======================

BrowserSync can automatically monitor your files for changes and inject your changes into the browser without requiring a manual refresh.

You can enable support for this by calling the mix.browserSync() method:

	<script>mix.browserSync('laravel.test');</script>

BrowserSync options can be specified by passing a JS object to the browserSync method:

	<script>
		mix.browserSync({
    		proxy: 'laravel.test'
		});
	</script>

Then, start webpack's development server by using <? npm run watch ?>.

Now, when you modify a script or PHP file, you can watch as the browser instantly refreshes the page to reflect your changes.


Environment Variables
======================

You can inject environment variables into your webpack.mix.js script by prefixing one of the environment variables in your .env file with <? MIX_ ?>:

	<? MIX_SENTRY_DSN_PUBLIC=http:example.com ?>

After the variable has been defined in your .env file, you can access it via the process.env object. However, you'll need to restart the task if the env variable's value changes while the task is running:

	<? process.env.MIX_SENTRY_DSN_PUBLIC ?>


Notifications
================

When available, Mix will automatically display OS notifications when compiling, giving you instant feedback as to whether the compilation was successful or not.

However, there may be instances when you would prefer to disable these notificatiosn. One such example could be triggering Mix on your production server.

Notifications can be deactivated using the disableNotifications method:

	<script>mix.disableNotifications();</script>


